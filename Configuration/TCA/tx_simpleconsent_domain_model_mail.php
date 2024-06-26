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
        'hideTable' => true,
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'subject, text_plain, text_html, status',
        'iconfile' => 'EXT:simple_consent/Resources/Public/Icons/tx_simpleconsent_domain_model_mail.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'hidden,--palette--;;1, subject, text_plain, text_html, status, reminder'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'crdate' => [
            'config' => [
                'type' => 'passthrough',
            ]
        ],
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
        'text_plain_footer' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.text_plain_footer',
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
        'text_html_footer' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.text_html_footer',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '15',
                'eval' => 'trim',
                'enableRichtext' => true
            ],
        ],
        'plugin_pid' => [
            'exclude' => false,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.plugin_pid',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkOptions' => 'file, folder, mail, telephone, url',
                        ],
                    ],
                ],
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
        'reminder' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.reminder',
            'config' => [
                'type' => 'check',
            ]
        ],
        'sent_tstamp' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.sent_tstamp',
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
        'reminder_tstamp' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:simple_consent/Resources/Private/Language/locallang_db.xlf:tx_simpleconsent_domain_model_mail.reminder_tstamp',
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
