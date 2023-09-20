<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail',
        'label' => 'last_name',
        'label_alt' => 'first_name, company, address, zip, city',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => ' first_name,last_name,address,zip,city,email,phone',
        'iconfile' => 'EXT:simple_consent/Resources/Public/Icons/tx_simpleconsent_domain_model_mail.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, title, text_plain, text_html',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden,--palette--;;1, text_plain, text_html, status'],
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


        'title' => [
            'label'=>'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.title',
            'exclude' => 0,
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'first_name' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.first_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'last_name' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.last_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'status' => [
            'label'=>'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.status',
            'exclude' => 0,
            'config'=>[
                'type' => 'select',
                'renderType' => 'selectSingle',
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 99,
                'items' => [
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.status.0', '0'],
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.status.1', '1'],
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.status.2', '2'],
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.status.99', '99'],
                ],
            ],
        ],
    ],
];
