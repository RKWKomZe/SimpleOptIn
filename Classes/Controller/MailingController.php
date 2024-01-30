<?php

namespace Madj2k\SimpleConsent\Controller;
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

use Madj2k\CoreExtended\Transfer\CsvImporter;
use Madj2k\SimpleConsent\Domain\Model\Address;
use Madj2k\SimpleConsent\Domain\Model\Mail;
use Madj2k\SimpleConsent\Domain\Repository\AddressRepository;
use Madj2k\SimpleConsent\Domain\Repository\MailRepository;
use Madj2k\SimpleConsent\Service\MailService;
use RKW\RkwNewsletter\Domain\Model\Approval;
use RKW\RkwNewsletter\Domain\Model\Issue;
use RKW\RkwNewsletter\Domain\Model\Newsletter;
use RKW\RkwNewsletter\Domain\Model\Topic;
use RKW\RkwNewsletter\Domain\Repository\BackendUserRepository;
use RKW\RkwNewsletter\Domain\Repository\IssueRepository;
use RKW\RkwNewsletter\Domain\Repository\NewsletterRepository;
use RKW\RkwNewsletter\Mailing\MailProcessor;
use RKW\RkwNewsletter\Manager\ApprovalManager;
use RKW\RkwNewsletter\Manager\IssueManager;
use RKW\RkwNewsletter\Status\IssueStatus;
use RKW\RkwNewsletter\Validation\EmailValidator;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * MailingController
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_SimpleConsent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class MailingController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * @var \Madj2k\SimpleConsent\Domain\Repository\MailRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected MailRepository  $mailRepository;


    /**
     * PersistenceManager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected PersistenceManager $persistenceManager;


    /**
     * Create new mail
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Mail|null $mail
     * @return void
     */
    public function createAction(Mail $mail = null): void
    {
        $this->view->assign('mail', $mail);
    }


    /**
     * Edit mail
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Mail|null $mail
     * @return void
     */
    public function editAction(Mail $mail): void
    {

        $mailList = $this->mailRepository->findByStatus(0);
        if (! count($mailList)) {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'mailingController.error.noMail',
                    'simple_consent'
                ),
                '',
                FlashMessage::ERROR
            );
        }

        $this->view->assignMultiple(
            [
                'mail' => $mail,
                'mailList' => $mailList
            ]
        );
    }


    /**
     * Save mail
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Mail|null $mail
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function saveAction(Mail $mail): void
    {

        $this->addFlashMessage(
            LocalizationUtility::translate(
            'mailingController.message.saved',
            'simple_consent'
             ),
            '',
            FlashMessage::OK
        );

        if ($mail->_isNew()) {
            $this->mailRepository->add($mail);
        } else {
            $this->mailRepository->update($mail);
        }

        $this->mailRepository->add($mail);
        $this->persistenceManager->persistAll();

        $this->redirect('import', null, null, ['mail' => $mail]);

    }


    /**
     * Import mask for CSV
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Mail|null $mail
     * @return void
     */
    public function importAction(Mail $mail = null): void
    {

        $mailList = $this->mailRepository->findByStatus(0);
        if (! count($mailList)) {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'mailingController.error.noMail',
                    'simple_consent'
                ),
                '',
                FlashMessage::ERROR
            );
        }

        $this->view->assignMultiple(
            [
                'mail' => $mail,
                'mailList' => $mailList
            ]
        );
    }


    /**
     * Do import of CSV
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Mail $mail
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function importSaveAction(Mail $mail): void
    {
        $fileType = $_FILES['tx_simpleconsent_tools_simpleconsentmanagement']['type']['file'];
        $filePath = $_FILES['tx_simpleconsent_tools_simpleconsentmanagement']['tmp_name']['file'];

        // check file type
        if (strtolower($fileType) != 'text/csv') {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'mailingController.error.wrongFileType',
                    'simple_consent'
                ),
                '',
                AbstractMessage::ERROR
            );

            $this->forward('import', null, null, ['mail' => $mail]);

        }

        $result = [];
        try {

            $csvImporter = $this->objectManager->get(CsvImporter::class);
            $csvImporter->setTableName('tx_simpleconsent_domain_model_address');

            // init importer and do some basic setup
            $csvImporter->readCsv($filePath);
            $csvImporter->setAllowedTables(
                [
                    'tx_simpleconsent_domain_model_address'
                ]
            );

            $csvImporter->setExcludeColumns(
                [
                    'tx_simpleconsent_domain_model_address' => ['status']
                ]
            );
            $csvImporter->setIncludeColumns(
                [
                    'tx_simpleconsent_domain_model_address' => ['pid']
                ]
            );

            // add a unique hash value to each record
            $csvImporter->setAdditionalData(['hash' => '']);
            $csvImporter->applyAdditionalData();

            $records = $csvImporter->getRecords();
            foreach ($records  as &$record) {
                $record['hash'] = \Madj2k\CoreExtended\Utility\GeneralUtility::getUniqueRandomString();
            }
            $csvImporter->setRecords($records);

            // do import
            $result = $csvImporter->import();

        } catch (\Exception $e) {

            $this->addFlashMessage(
                sprintf(
                    LocalizationUtility::translate(
                        'mailingController.error.importFailed',
                        'simple_consent',
                     ),
                    $e->getMessage()
                ),
                '',
                AbstractMessage::ERROR,
            );
        }

        if ($result) {

            /** @var \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool */
            $connectionPool = \Madj2k\CoreExtended\Utility\GeneralUtility::makeInstance(ConnectionPool::class);

            // set relations. We use the QueryBuilder, because it is much faster
            /** @var \TYPO3\CMS\Core\Database\Query\QueryBuilder $queryBuilder */
            $queryBuilder = $connectionPool->getQueryBuilderForTable('tx_simpleconsent_domain_model_mail');
            $queryBuilder->getRestrictions()->removeAll();

            $queryBuilder
                ->update('tx_simpleconsent_domain_model_mail')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($mail->getUid(), \PDO::PARAM_INT)
                    )
                )->set('addresses', implode(',', array_keys($result)))
                ->set('status', 1);


            if ($queryBuilder->execute()) {
                $this->addFlashMessage(
                    sprintf(
                        LocalizationUtility::translate(
                            'mailingController.message.importSuccessful',
                            'simple_consent',
                        ),
                        count($result)
                    ),
                    '',
                    AbstractMessage::OK
                );

                $this->forward('prepareTest', null, null, ['mail' => $mail]);
            }
        }

        $this->addFlashMessage(
            LocalizationUtility::translate(
                'mailingController.warning.nothingImported',
                'simple_consent',
            ),
            '',
            AbstractMessage::WARNING
        );

        $this->forward('import', null, null, ['mail' => $mail]);

    }


    /**
     * Prepare test mail
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Mail|null $mail
     * @param string $emailAddress
     * @return void
     */
    public function prepareTestAction(Mail $mail = null, string $emailAddress = ''): void
    {

        $mailList = $this->mailRepository->findByStatus(1);
        if (! count($mailList)) {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'mailingController.error.noMail',
                    'simple_consent'
                ),
                '',
                AbstractMessage::ERROR
            );
        }

        $this->view->assignMultiple(
            [
                'mail' => $mail,
                'emailAddress' => $emailAddress,
                'mailList' => $mailList
            ]
        );
    }


    /**
     * Send test mail
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Mail $mail
     * @param string $emailAddress
     * @return void
     * @throws StopActionException
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function sendTestAction(Mail $mail, string $emailAddress = ''): void
    {
        if (! GeneralUtility::validEmail($emailAddress)) {

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'mailingController.error.invalidEmail',
                    'simple_consent',
                ),
                '',
                AbstractMessage::ERROR
            );

            $this->forward(
                'prepareTest',
                null,
                null,
                [
                    'mail' => $mail,
                    'emailAddress' => $emailAddress
                ]
            );
        }

        $mailService = GeneralUtility::makeInstance(MailService::class);
        if ($mailService->sendConsentEmail(
            [
                'first_name' => 'Sam',
                'last_name' => 'Muster',
                'email' => $emailAddress,
                'hash' => 'abcdefghijklmnopqrstuvwxyz1234' // we need 20 signs for the hash

            ],
            $mail
        )) {

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'mailingController.message.testMailSent',
                    'simple_consent',
                ),
                '',
                AbstractMessage::OK
            );

        } else {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'mailingController.error.testMailNotSent',
                    'simple_consent',
                ),
                '',
                AbstractMessage::ERROR
            );
        }

        $this->forward(
            'prepareTest',
            null,
            null,
            [
                'mail' => $mail,
                'emailAddress' => $emailAddress
            ]
        );

    }


    /**
     * Prepare sending
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Mail|null $mail
     * @return void
     */
    public function prepareSendAction(Mail $mail = null): void
    {

        $mailList = $this->mailRepository->findByStatus(1);
        if (! count($mailList)) {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'mailingController.error.noMail',
                    'simple_consent'
                ),
            '',
                AbstractMessage::ERROR
            );
        }

        $this->view->assignMultiple(
            [
                'mail' => $mail,
                'mailList' => $mailList
            ]
        );
    }


    /**
     * Prepare sending
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Mail|null $mail
     * @param int $reassure
     * @return void
     * @throws StopActionException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function sendAction(Mail $mail, int $reassure = 0): void
    {
        if (! $reassure) {

            $this->addFlashMessage(
                LocalizationUtility::translate(
                'mailingController.error.pleaseReassure',
                'simple_consent'
                ),
                '',
                AbstractMessage::ERROR
            );

            $this->forward('prepareSend');

        }

        $mail->setStatus(2);
        $this->mailRepository->update($mail);
        $this->persistenceManager->persistAll();

        $this->addFlashMessage(
            LocalizationUtility::translate(
                'mailingController.message.mailSent',
                'simple_consent',
            ),
            '',
            AbstractMessage::OK
        );

        $this->forward('sent');
    }



    /**
     * Prepare reminder
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Mail|null $mail
     * @return void
     */
    public function prepareReminderAction(Mail $mail = null): void
    {
        $mailList = $this->mailRepository->findByStatus(3);
        if (! count($mailList)) {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'mailingController.error.noMail',
                    'simple_consent'
                ),
                '',
                AbstractMessage::ERROR
            );
        }

        $this->view->assignMultiple(
            [
                'mail' => $mail,
                'mailList' => $mailList
            ]
        );
    }


    /**
     * Send reminder
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Mail|null $mail
     * @param int $reassure
     * @return void
     * @throws StopActionException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function reminderAction(Mail $mail, int $reassure = 0): void
    {
        if (! $reassure) {

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'mailingController.error.pleaseReassure',
                    'simple_consent'
                ),
                '',
                AbstractMessage::ERROR
            );

            $this->forward('prepareReminder');
        }

        $mail->unsetQueueMail();
        $mail->setReminder(1);
        $mail->setStatus(2);
        $this->mailRepository->update($mail);
        $this->persistenceManager->persistAll();

        $this->addFlashMessage(
            LocalizationUtility::translate(
                'mailingController.message.mailSent',
                'simple_consent',
            ),
            '',
            AbstractMessage::OK
        );

        $this->forward('sent');
    }


    /**
     * Sent e-Mails
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Mail|null $mail
     * @return void
     */
    public function sentAction(Mail $mail = null): void
    {
        $mailListSending = $this->mailRepository->findByStatus(2);
        $mailListSent = $this->mailRepository->findByStatus(3);
        if (! count($mailListSent) && ! count($mailListSending)) {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'mailingController.error.noMail',
                    'simple_consent'
                ),
                '',
                AbstractMessage::ERROR
            );
        }

        $this->view->assignMultiple(
            [
                'mail' => $mail,
                'mailListSent' => $mailListSent,
                'mailListSending' => $mailListSending,
            ]
        );
    }
}
