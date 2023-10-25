<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address',
        'label' => 'last_name',
        'label_alt' => 'first_name, company, address, zip, city',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'hideTable' => true,
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => ' first_name,last_name,address,zip,city,email,phone',
        'iconfile' => 'EXT:simple_consent/Resources/Public/Icons/tx_simpleconsent_domain_model_address.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, title, gender, company, first_name, last_name, address, zip, city, email, phone, status, feedback_tstamp, feedback_ip',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden,--palette--;;1, title, gender, company, first_name, last_name, address, zip, city, email, phone, status, feedback_tstamp, feedback_ip'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [


        'hidden' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],

        'gender' => [
            'label'=>'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.gender',
            'exclude' => 0,
            'config'=>[
                'type' => 'select',
                'renderType' => 'selectSingle',
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 99,
                'items' => [
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.gender.0', '0'],
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.gender.1', '1'],
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.gender.2', '2'],
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.gender.99', '99'],
                ],
            ],
        ],
        'title' => [
            'label'=>'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.title',
            'exclude' => 0,
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'first_name' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.first_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'last_name' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.last_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'company' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.company',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'address' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.address',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'zip' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.zip',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ],
        ],
        'city' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.city',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'phone' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.phone',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'email' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'hash' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.hash',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'status' => [
            'label'=>'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.status',
            'exclude' => 0,
            'config'=>[
                'type' => 'select',
                'renderType' => 'selectSingle',
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 99,
                'items' => [
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.status.0', '0'],
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.status.1', '1'],
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.status.2', '2'],
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.status.3', '3'],
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.status.99', '99'],
                ],
            ],
            'feedback_tstamp' => [
                'exclude' => 0,
                'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.feedback_tstamp',
                'config' => [
                    'type'    => 'input',
                    'renderType' => 'inputDateTime',
                    'size'    => 13,
                    'eval'    => 'datetime',
                    'default' => 0,
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ]
                ],
            ],
            'feedback_ip' => [
                'exclude' => 0,
                'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_address.feedback_ip',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim'
                ],
            ],

        ],
    ],
];
