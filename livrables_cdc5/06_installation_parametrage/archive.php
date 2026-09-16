<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dossier numérique (mallette) — archivage
    |--------------------------------------------------------------------------
    | Types de documents admis et rétention par défaut (mois), alignés CDC §1.14.
    | La rétention peut être surchargée par document (colonne retention_months).
    */

    'types' => [
        'recu',
        'convention',
        'rapport_avancement',
        'rapport_rapporteur',
        'decision',
        'pv',
        'attestation',
        'piece',
        'these',
        'autre',
    ],

    'retention' => [
        'attestation' => 120,  // 10 ans — dossier électronique du doctorant
        'pv' => 120,
        'decision' => 120,
        'these' => 360,        // pérennité des thèses
        'default' => 60,       // 5 ans
    ],

    /*
    |--------------------------------------------------------------------------
    | Stockage des pièces
    |--------------------------------------------------------------------------
    | Règle d'or P7 : les pièces uploadées passent par le stockage public de
    | l'app (+ références en base). Le package reste moteur de documents.
    */

    'disk' => env('ARCHIVE_DISK', 'public'),

    'prefix' => 'documents',

];
