<?php
namespace Madj2k\SimpleConsent\Service;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Madj2k\CoreExtended\Utility\GeneralUtility;
use Madj2k\Postmaster\Mail\MailMessage;
use Madj2k\SimpleConsent\Domain\Model\Mail;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * MailService
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_SimpleConsent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class MailService implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * Handles Consent Mail
     *
     * @param array $recipient
     * @param \Madj2k\SimpleConsent\Domain\Model\Mail $mail
     * @param \Madj2k\Postmaster\Mail\MailMessage|null $mailMessage
     * @return bool
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function sendConsentEmail(array $recipient, Mail $mail, MailMessage $mailMessage = null): bool
    {

        $settings = $this->getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = $this->getSettings();

        if ($settings['view']['templateRootPaths']) {

            if (! $mailMessage) {
                /** @var \Madj2k\Postmaster\Mail\MailMessage $mailMessage */
                $mailMessage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(MailMessage::class);
            }

            // add recipients
            $mailMessage->setTo(
                [
                    'salutation' =>  $recipient['salutation'],
                    'firstName' => $recipient['first_name'],
                    'lastName' => $recipient['last_name'],
                    'email' => $recipient['email']
                ],
                [
                    'marker' => [
                        'recipient'       => $recipient,
                        'mail'            => $mail,
                        'settings'        => $settingsDefault,
                    ],
                ],
                true
            );

            $mailMessage->getQueueMail()->setSettingsPid($mail->getPluginPid());
            $mailMessage->getQueueMail()->setSubject($mail->getSubject());
            $mailMessage->getQueueMail()->addTemplatePaths($settings['view']['templateRootPaths']);
            $mailMessage->getQueueMail()->setPlaintextTemplate('Email/Consent');
            $mailMessage->getQueueMail()->setHtmlTemplate('Email/Consent');

            return $mailMessage->send();
        }

        return false;
    }


    /**
     * Returns TYPO3 settings
     *
     * @param string $which Which type of settings will be loaded
     * @return array
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function getSettings(string $which = ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS): array
    {
        return GeneralUtility::getTypoScriptConfiguration('simpleConsent', $which);
    }
}
