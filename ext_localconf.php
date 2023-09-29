<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Madj2k.' . $extKey,
            'Consent',
            [
                'Consent' => 'show, confirm, delete',
            ],
            // non-cacheable actions
            [
                'Consent' => 'show, confirm, delete',
            ]
        );


        //=================================================================
        // Add TypoScript automatically
        //=================================================================
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
            'Accelerator',
            'constants',
            '<INCLUDE_TYPOSCRIPT: source="FILE: EXT:simple_consent/Configuration/TypoScript/constants.typoscript">'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
            'Accelerator',
            'setup',
            '<INCLUDE_TYPOSCRIPT: source="FILE: EXT:simple_consent/Configuration/TypoScript/setup.typoscript">'
        );

        //=================================================================
        // Register Logger
        //=================================================================
        $GLOBALS['TYPO3_CONF_VARS']['LOG']['Madj2k']['SimpleOptIn']['writerConfiguration'] = [

            // configuration for WARNING severity, including all
            // levels with higher severity (ERROR, CRITICAL, EMERGENCY)
            \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
                // add a FileWriter
                'TYPO3\\CMS\\Core\\Log\\Writer\\FileWriter' => [
                    // configuration for the writer
                    'logFile' => \TYPO3\CMS\Core\Core\Environment::getVarPath()  . '/log/tx_simpleconsent.log'
                ]
            ],
        ];


    },
    $_EXTKEY
);
