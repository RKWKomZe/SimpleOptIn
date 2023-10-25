<?php
declare(strict_types = 1);

return [
    \Madj2k\CoreExtended\Domain\Model\BackendUser::class => [
        'tableName' => 'be_users',
    ],
    \Madj2k\SimpleConsent\Domain\Model\Mail::class => [
        'properties' => [
            'uid' => [
                'fieldName' => 'uid'
            ],
            'pid' => [
                'fieldName' => 'pid'
            ],
            'tstamp' => [
                'fieldName' => 'tstamp'
            ],
        ],
    ],
];
