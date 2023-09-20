<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(

    function (string $extKey) {

        //=================================================================
        // Register Plugins
        //=================================================================
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Madj2k.SimpleConsent',
            'Consent',
            'SimpleConsent: Show / Confirm / Delete'
        );

    },
    'simple_consent'
);
