<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use SalsabilEnnaiem\PvModule\Models\PvTemplate;

class UmaDocumentTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPv();
        $this->seedAttestation();
        $this->seedDecision();
        $this->seedArrete();
        $this->seedInvitation();
        $this->seedDiplome();
        $this->seedFicheAcces();
    }

    private function seedPv(): void
    {
        PvTemplate::updateOrCreate(
            ['user_id' => null, 'type' => 'pv'],
            [
                'config' => [
                    'sections' => [
                        ['id' => 'header', 'fixed' => true, 'title' => 'En-tête du PV', 'fields' => ['titre', 'date'], 'styles' => ['titleColor' => '#1d4ed8', 'titleSize' => 15, 'font' => 'DejaVu Sans', 'textColor' => '#0f172a', 'textSize' => 11], 'margin' => ['top' => 0, 'bottom' => 10, 'left' => 0, 'right' => 0]],
                        ['id' => 'participants', 'fixed' => false, 'title' => 'Participants', 'fields' => ['participants'], 'styles' => ['titleColor' => '#059669', 'titleSize' => 13, 'font' => 'DejaVu Sans', 'textColor' => '#0f172a', 'textSize' => 11], 'margin' => ['top' => 8, 'bottom' => 8, 'left' => 0, 'right' => 0]],
                        ['id' => 'contenu', 'fixed' => false, 'title' => 'Contenu & Délibérations', 'fields' => ['contenu'], 'styles' => ['titleColor' => '#7c3aed', 'titleSize' => 13, 'font' => 'DejaVu Sans', 'textColor' => '#0f172a', 'textSize' => 11], 'margin' => ['top' => 8, 'bottom' => 8, 'left' => 0, 'right' => 0]],
                        ['id' => 'signature', 'fixed' => true, 'title' => 'Clôture & Signatures', 'fields' => ['signatures'], 'styles' => ['titleColor' => '#64748b', 'titleSize' => 12, 'font' => 'DejaVu Sans', 'textColor' => '#334155', 'textSize' => 10], 'margin' => ['top' => 20, 'bottom' => 0, 'left' => 0, 'right' => 0]],
                    ],
                    'margins' => ['top' => 20, 'bottom' => 20, 'left' => 20, 'right' => 20],
                ],
                'orientation' => 'portrait',
                'is_default' => true,
                'is_custom' => false,
            ]
        );
    }

    private function seedAttestation(): void
    {
        PvTemplate::updateOrCreate(
            ['user_id' => null, 'type' => 'attestation'],
            [
                'config' => [
                    'sections' => [
                        ['id' => 'header', 'fixed' => true, 'title' => "Attestation d'inscription", 'fields' => ['titre', 'destinataire', 'etablissement'], 'styles' => ['titleColor' => '#1e40af', 'titleSize' => 16, 'font' => 'DejaVu Sans', 'textColor' => '#0f172a', 'textSize' => 12], 'margin' => ['top' => 0, 'bottom' => 16, 'left' => 0, 'right' => 0]],
                        ['id' => 'contenu', 'fixed' => false, 'title' => 'Objet de l\'attestation', 'fields' => ['contenu'], 'styles' => ['titleColor' => '#1e40af', 'titleSize' => 13, 'font' => 'DejaVu Sans', 'textColor' => '#1e293b', 'textSize' => 12], 'margin' => ['top' => 8, 'bottom' => 8, 'left' => 0, 'right' => 0]],
                        ['id' => 'signature', 'fixed' => true, 'title' => 'Signature(s) autorisée(s)', 'fields' => ['signatures'], 'styles' => ['titleColor' => '#475569', 'titleSize' => 12, 'font' => 'DejaVu Sans', 'textColor' => '#334155', 'textSize' => 10], 'margin' => ['top' => 30, 'bottom' => 0, 'left' => 0, 'right' => 0]],
                    ],
                    'margins' => ['top' => 25, 'bottom' => 25, 'left' => 30, 'right' => 30],
                ],
                'orientation' => 'portrait',
                'is_default' => true,
                'is_custom' => false,
            ]
        );
    }

    private function seedDecision(): void
    {
        PvTemplate::updateOrCreate(
            ['user_id' => null, 'type' => 'decision'],
            [
                'config' => [
                    'sections' => [
                        ['id' => 'header', 'fixed' => true, 'title' => 'Décision de la commission', 'fields' => ['titre', 'reference'], 'styles' => ['titleColor' => '#b91c1c', 'titleSize' => 15, 'font' => 'DejaVu Sans', 'textColor' => '#0f172a', 'textSize' => 12], 'margin' => ['top' => 0, 'bottom' => 12, 'left' => 0, 'right' => 0]],
                        ['id' => 'contenu', 'fixed' => false, 'title' => 'Texte de la décision', 'fields' => ['contenu'], 'styles' => ['titleColor' => '#b91c1c', 'titleSize' => 13, 'font' => 'DejaVu Sans', 'textColor' => '#1e293b', 'textSize' => 12], 'margin' => ['top' => 8, 'bottom' => 8, 'left' => 0, 'right' => 0]],
                        ['id' => 'email_body', 'fixed' => false, 'title' => 'Modèle de notification email', 'fields' => ['email_body'], 'styles' => ['titleColor' => '#9333ea', 'titleSize' => 12, 'font' => 'DejaVu Sans', 'textColor' => '#4b5563', 'textSize' => 11], 'margin' => ['top' => 8, 'bottom' => 8, 'left' => 0, 'right' => 0]],
                        ['id' => 'signature', 'fixed' => true, 'title' => 'Signature(s)', 'fields' => ['signatures'], 'styles' => ['titleColor' => '#475569', 'titleSize' => 12, 'font' => 'DejaVu Sans', 'textColor' => '#334155', 'textSize' => 10], 'margin' => ['top' => 25, 'bottom' => 0, 'left' => 0, 'right' => 0]],
                    ],
                    'margins' => ['top' => 25, 'bottom' => 25, 'left' => 25, 'right' => 25],
                ],
                'orientation' => 'portrait',
                'is_default' => true,
                'is_custom' => false,
            ]
        );
    }

    private function seedArrete(): void
    {
        PvTemplate::updateOrCreate(
            ['user_id' => null, 'type' => 'arrete'],
            [
                'config' => [
                    'sections' => [
                        ['id' => 'header', 'fixed' => true, 'title' => 'Arrêté universitaire', 'fields' => ['titre', 'reference', 'universite'], 'styles' => ['titleColor' => '#0369a1', 'titleSize' => 16, 'font' => 'DejaVu Sans', 'textColor' => '#0f172a', 'textSize' => 12], 'margin' => ['top' => 0, 'bottom' => 14, 'left' => 0, 'right' => 0]],
                        ['id' => 'contenu', 'fixed' => false, 'title' => 'Texte de l\'arrêté', 'fields' => ['contenu'], 'styles' => ['titleColor' => '#0369a1', 'titleSize' => 13, 'font' => 'DejaVu Sans', 'textColor' => '#1e293b', 'textSize' => 12], 'margin' => ['top' => 8, 'bottom' => 8, 'left' => 0, 'right' => 0]],
                        ['id' => 'signature', 'fixed' => true, 'title' => 'Signature(s) autorisée(s)', 'fields' => ['signatures'], 'styles' => ['titleColor' => '#475569', 'titleSize' => 12, 'font' => 'DejaVu Sans', 'textColor' => '#334155', 'textSize' => 10], 'margin' => ['top' => 30, 'bottom' => 0, 'left' => 0, 'right' => 0]],
                    ],
                    'margins' => ['top' => 25, 'bottom' => 25, 'left' => 30, 'right' => 30],
                ],
                'orientation' => 'portrait',
                'is_default' => true,
                'is_custom' => false,
            ]
        );
    }

    private function seedInvitation(): void
    {
        PvTemplate::updateOrCreate(
            ['user_id' => null, 'type' => 'invitation'],
            [
                'config' => [
                    'sections' => [
                        ['id' => 'header', 'fixed' => true, 'title' => 'Invitation officielle', 'fields' => ['titre', 'organisateur', 'destinataire'], 'styles' => ['titleColor' => '#1d4ed8', 'titleSize' => 16, 'font' => 'DejaVu Sans', 'textColor' => '#0f172a', 'textSize' => 12], 'margin' => ['top' => 0, 'bottom' => 14, 'left' => 0, 'right' => 0]],
                        ['id' => 'contenu', 'fixed' => false, 'title' => 'Détails de l\'invitation', 'fields' => ['contenu', 'lieu', 'date_reunion', 'ordre_du_jour'], 'styles' => ['titleColor' => '#1d4ed8', 'titleSize' => 13, 'font' => 'DejaVu Sans', 'textColor' => '#1e293b', 'textSize' => 12], 'margin' => ['top' => 8, 'bottom' => 8, 'left' => 0, 'right' => 0]],
                        ['id' => 'signature', 'fixed' => true, 'title' => 'Signature(s)', 'fields' => ['signatures'], 'styles' => ['titleColor' => '#475569', 'titleSize' => 12, 'font' => 'DejaVu Sans', 'textColor' => '#334155', 'textSize' => 10], 'margin' => ['top' => 30, 'bottom' => 0, 'left' => 0, 'right' => 0]],
                    ],
                    'margins' => ['top' => 30, 'bottom' => 30, 'left' => 30, 'right' => 30],
                ],
                'orientation' => 'portrait',
                'is_default' => true,
                'is_custom' => false,
            ]
        );
    }

    private function seedDiplome(): void
    {
        PvTemplate::updateOrCreate(
            ['user_id' => null, 'type' => 'diplome'],
            [
                'config' => [
                    'sections' => [
                        ['id' => 'header', 'fixed' => true, 'title' => 'Diplôme universitaire (MESRS)', 'fields' => ['titre', 'universite', 'etablissement', 'filiere'], 'styles' => ['titleColor' => '#1e3a5f', 'titleSize' => 16, 'font' => 'DejaVu Sans', 'textColor' => '#0f172a', 'textSize' => 12], 'margin' => ['top' => 0, 'bottom' => 14, 'left' => 0, 'right' => 0]],
                        ['id' => 'contenu', 'fixed' => false, 'title' => 'Mention & Spécialité', 'fields' => ['contenu', 'mention', 'specialite'], 'styles' => ['titleColor' => '#1e3a5f', 'titleSize' => 13, 'font' => 'DejaVu Sans', 'textColor' => '#1e293b', 'textSize' => 12], 'margin' => ['top' => 8, 'bottom' => 8, 'left' => 0, 'right' => 0]],
                        ['id' => 'signature', 'fixed' => true, 'title' => 'Signature(s) & Sceau', 'fields' => ['signatures'], 'styles' => ['titleColor' => '#475569', 'titleSize' => 12, 'font' => 'DejaVu Sans', 'textColor' => '#334155', 'textSize' => 10], 'margin' => ['top' => 30, 'bottom' => 0, 'left' => 0, 'right' => 0]],
                    ],
                    'margins' => ['top' => 25, 'bottom' => 25, 'left' => 30, 'right' => 30],
                ],
                'orientation' => 'landscape',
                'is_default' => true,
                'is_custom' => false,
            ]
        );
    }

    private function seedFicheAcces(): void
    {
        PvTemplate::updateOrCreate(
            ['user_id' => null, 'type' => 'fiche_acces'],
            [
                'config' => [
                    'sections' => [
                        ['id' => 'header', 'fixed' => true, 'title' => 'Fiche d\'accès — Paramètres de connexion', 'fields' => ['titre', 'destinataire'], 'styles' => ['titleColor' => '#0d9488', 'titleSize' => 15, 'font' => 'DejaVu Sans', 'textColor' => '#0f172a', 'textSize' => 12], 'margin' => ['top' => 0, 'bottom' => 14, 'left' => 0, 'right' => 0]],
                        ['id' => 'contenu', 'fixed' => false, 'title' => 'Paramètres d\'accès', 'fields' => ['contenu', 'login', 'mot_de_passe'], 'styles' => ['titleColor' => '#0d9488', 'titleSize' => 13, 'font' => 'DejaVu Sans', 'textColor' => '#1e293b', 'textSize' => 12], 'margin' => ['top' => 8, 'bottom' => 8, 'left' => 0, 'right' => 0]],
                        ['id' => 'signature', 'fixed' => true, 'title' => 'Émis par', 'fields' => ['signatures'], 'styles' => ['titleColor' => '#475569', 'titleSize' => 11, 'font' => 'DejaVu Sans', 'textColor' => '#334155', 'textSize' => 10], 'margin' => ['top' => 20, 'bottom' => 0, 'left' => 0, 'right' => 0]],
                    ],
                    'margins' => ['top' => 20, 'bottom' => 20, 'left' => 20, 'right' => 20],
                ],
                'orientation' => 'portrait',
                'is_default' => true,
                'is_custom' => false,
            ]
        );
    }
}
