<?php

return [
    'compliance' => [
        'signature_driver' => env('SIGNATURE_DRIVER', 'simple_image'),
        'qualified' => [
            'allow_without_certificate' => env('SIGNATURE_ALLOW_WITHOUT_CERTIFICATE', false),
        ],
        'pv_template_version' => 'v1',
    ],

    // Seuils d'approbation (ApprovalRules, contrat package)
    'approval' => [
        'mode' => env('PV_APPROVAL_MODE', 'unanimous'),
        'quorum_percent' => (int) env('PV_APPROVAL_QUORUM_PERCENT', 100),
    ],
];
