<?php
declare(strict_types = 1);

return [
    \Madj2k\CoreExtended\Domain\Model\BackendUser::class => [
        'tableName' => 'be_users',
    ],
    \Madj2k\SimpleConsent\Domain\Model\Mail::class => [
        'tableName' => 'tx_simpleconsent_domain_model_mail',
        'properties' => [
            'crdate' => [
                'fieldName' => 'crdate'
            ],
        ],
    ],
];
