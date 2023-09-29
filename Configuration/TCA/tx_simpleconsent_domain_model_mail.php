<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail',
        'label' => 'subject',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'subject, text_plain, text_html, status',
        'iconfile' => 'EXT:simple_consent/Resources/Public/Icons/tx_simpleconsent_domain_model_mail.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, subject, text_plain, text_html',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden,--palette--;;1, subject, text_plain, text_html, status'],
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
        'subject' => [
            'label'=>'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.subject',
            'exclude' => 0,
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'text_plain' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.text_plain',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '15',
                'eval' => 'trim',
            ],
        ],
        'text_html' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.text_html',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '15',
                'eval' => 'trim',
                'enableRichtext' => true
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
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.status.2', '3'],
                    ['LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.status.99', '99'],
                ],
            ],
        ],
        'addresses' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.address',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'foreign_table' => 'tx_simpleconsent_domain_model_address',
                'foreign_table_where' => 'AND status = 0 ORDER BY email ASC, crdate ASC',
                'minitems' => 1,
                'maxitems' => 999999999,
            ]
        ],
        'queue_mail' => [
            'config' => [
                'type' => 'passthrough',
                'foreign_table' => 'tx_postmaster_domain_model_queuemail',
            ],
        ],
    ],
];
