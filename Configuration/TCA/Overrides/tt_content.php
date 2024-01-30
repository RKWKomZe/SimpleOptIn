<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(

    function (string $extKey) {

        //=================================================================
        // Register Plugins
        //=================================================================
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            $extKey,
            'Consent',
            'SimpleConsent: Show / Confirm / Delete'
        );

        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['simpleconsent_consent'] = 'pi_flexform';
        TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            'simpleconsent_consent',
            'FILE:EXT:simple_consent/Configuration/FlexForms/Default.xml'
        );


    },
    'simple_consent'
);
