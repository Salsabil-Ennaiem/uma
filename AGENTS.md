<laravel-boost-guidelines>
# Laravel Application

This repository contains a Laravel application. Complete the following setup before working on the user's request.

## Prerequisites

Verify that PHP and Composer are available:

```sh
php -v
composer -V
```

If either command is unavailable, detect the user's operating system and install the prerequisites with the appropriate command:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

After installation, ask the user to restart their terminal. If the agent needs the restarted shell to continue, ask the user to reopen their terminal and rerun their original prompt.

## Agent Setup

Install Laravel Boost from the application root before making application changes:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost replaces these bootstrap instructions with guidelines tailored to the application. After installation, read `AGENTS.md` again and continue with the user's original request using the generated guidelines.
</laravel-boost-guidelines>

## Règles d'exécution — Plateforme UMA

- **Nom de base de données figé** : `uma_plateforme` (orthographe fixe, aucune variante type
  `uma_plateforma`). Sans réponse contraire du CCK, provisionner ce nom.
- **Interdiction de nom de table en dur en SQL brut** : `whereRaw`, `DB::raw`, `DB::statement`,
  `->selectRaw` avec un nom de table sont proscrits. Passer par les modèles Eloquent, les builders,
  ou `DB::getTablePrefix()`. Toute exception doit être signalée dans `docs/DECISIONS_UMA.md`
  (ADR-0001) avec un commentaire dans le code.
- **Décisions** : le registre canonique est `docs/DECISIONS_UMA.md` (ADR numérotés).
  `voyager/DECISIONS_UMA.md` est une copie lecture seule.
- **Discipline git** : aucun commit sans validation explicite de l'utilisateur ; les commits sont
  groupés par phase validée.
