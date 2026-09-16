# Dossier de déploiement — CCK / RNU (P9)

> **Statut** : livré le 2026-09-15. Document de production pour la mise en exploitation.

## 1. Environnement de production (cible CCK)

| Élément | Spécification |
|---|---|
| OS serveur | Ubuntu 22.04 LTS (CCK) |
| PHP | 8.3 (pdo_mysql, mbstring, openssl, tokenizer, fileinfo, exif, bcmath) |
| Serveur web | Nginx 1.18+ |
| Base de données | MySQL 8.x (InnoDB, utf8mb4_unicode_ci) |
| SSL | Let's Encrypt (auto-renouvellement certbot) |
| Filesystem distant | S3-compatible (CCK object storage) |
| Cron | `* * * * * php /var/www/uma/artisan schedule:run >> /dev/null 2>&1` |

## 2. Prérequis serveur

```bash
sudo apt update
sudo apt install -y php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml \
    php8.3-curl php8.3-zip php8.3-bcmath php8.3-gd php8.3-intl php8.3-exif \
    mysql-server nginx certbot python3-certbot-nginx composer git
sudo systemctl enable nginx mysql php8.3-fpm
```

## 3. Installation de l'application

```bash
cd /var/www
sudo git clone <depot-uma> uma
cd uma
sudo chown -R www-data:www-data .
composer install --no-dev --optimize-autoloader --no-interaction
cp .env.example .env
sudo php artisan key:generate --force

# .env : APP_ENV=production, APP_URL=https://uma.exemple.tn,
#   DB_CONNECTION=mysql, DB_DATABASE=uma_prod, DB_USERNAME/sen, DB_PASSWORD
#   SESSION_DRIVER=database, QUEUE_CONNECTION=database, FILESYSTEM_DISK=local|s3,
#   MAIL_MAILER=smtp, LOG_CHANNEL=daily LOG_LEVEL=warning

sudo php artisan migrate --force
sudo php artisan storage:link --force
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache
sudo php artisan event:cache
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

> **Note** : `config:cache` doit être lancée **après** avoir ﬁnalisé `.env`.

## 4. Configuration Nginx (vhost)

```nginx
server {
    listen 80; server_name uma.exemple.tn; return 301 https://$host$request_uri;
}
server {
    listen 443 ssl http2;
    server_name uma.exemple.tn;
    root /var/www/uma/public;
    index index.php index.html;
    ssl_certificate     /etc/letsencrypt/live/uma.exemple.tn/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/uma.exemple.tn/privkey.pem;
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header Referrer-Policy "strict-origin-when-cross-origin";
    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    location ~ /\.(?!well-known).* { deny all; }
}
```

```bash
sudo nginx -t
sudo ln -s /etc/nginx/sites-available/uma.conf /etc/nginx/sites-enabled/
sudo systemctl reload nginx
sudo certbot --nginx -d uma.exemple.tn
sudo systemctl reload nginx
```

## 5. File d'attente (queue)

```ini
# /etc/systemd/system/uma-queue.service
[Unit]
Description=UMA Queue Worker
After=network.target mysql.service
[Service]
User=www-data
Group=www-data
WorkingDirectory=/var/www/uma
ExecStart=/usr/bin/php artisan queue:work --sleep=3 --tries=3 --max-time=3600
Restart=always
RestartSec=5
[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now uma-queue.service
```

## 6. Scheduler (cron)

| Commande | Fréquence | Objet |
|---|---|---|
| `pv-module:check-deadlines` | journalier 08h | Rappels PV |
| `pv-module:notify-rappels` | journalier 08h | Relances signature |
| `p7:archive-old-documents` | journalier 02h | Archivage P7 |
| `p7:create-backup-snapshot` | journalier 02h30 | Snapshot |
| `p8:run-workflow-transitions` | tot les 5 min | Workflow |

## 7. Sauvegardes

| Élément | Méthode | Rétention |
|---|---|---|
| Base MySQL | `db:backup` | 30 jours |
| WAL | rétention 30j | 30 jours |
| Storage privée | `backup:run --filesystem=local` (P7) | 30 jours |

```bash
php artisan db:backup --connection=mysql
php artisan backup:run --filesystem=local
php artisan p7:create-backup-snapshot
```

## 8. Supervision et logs

- `storage/logs/laravel-*.log` — rotation quotidienne.
- `pail` — monitoring live.
- `p7:check-audit-integrity` — intégrité des fichiers archivés.

## 9. CI / CD

| Étape | Outil |
|---|---|
| Analyse statique | Laravel Pint |
| Tests | Pest (87 tests / 429 assertions) |
| Audit sécurité | `composer audit` |
| Déploiement | Deployer SSH |
| Post-déploiement | `migrate --force`, `config:cache`, `storage:link`, `queue:restart` |

## 10. Checklist Go-live

- [ ] `.env` renseigné (APP_ENV=production, DB, SSL, MAIL)
- [ ] `config:cache` + `route:cache` + `view:cache`
- [ ] `php artisan migrate --force`
- [ ] `storage:link` + perms 775
- [ ] Worker queue actif (`systemctl status uma-queue`)
- [ ] Cron `schedule:run` opérationnel
- [ ] SSL actif (`certbot certificates`)
- [ ] Test de backup effectué
- [ ] `composer audit` 0 advisory

## 11. Rollback

```bash
php artisan migrate:rollback        # annuler la dernière migration
php artisan db:restore <dump.sql>   # restaurer la base
php artisan config:clear && php artisan config:cache
php artisan queue:restart
sudo systemctl reload nginx
```