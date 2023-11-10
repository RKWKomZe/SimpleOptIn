<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        //=================================================================
        // Register BackendModule
        //=================================================================
        if (TYPO3_MODE === 'BE') {

            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                $extKey,
                'tools',	 		// Make module a submodule of 'tools'
                'management',		// Submodule key
                '',					// Position
                [
                    \Madj2k\SimpleConsent\Controller\MailingController::class => 'create, edit, save, import, importSave, prepareTest, sendTest, prepareSend, send',
                ],
                [
                    'access' => 'user,group',
                    'icon'   => 'EXT:' . $extKey . '/ext_icon.gif',
                    'labels' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_backend.xlf',
                ]
            );
        }


        //=================================================================
        // Add tables
        //=================================================================
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
            'tx_simpleconsent_domain_model_address'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
            'tx_simpleconsent_domain_model_mail'
        );

    },
    'simple_consent'
);

