<?php

namespace Madj2k\SimpleConsent\Command;
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

use Madj2k\Postmaster\Mail\MailMessage;
use Madj2k\Postmaster\Validation\QueueMailValidator;
use Madj2k\SimpleConsent\Domain\Model\Address;
use Madj2k\SimpleConsent\Domain\Repository\AddressRepository;
use Madj2k\SimpleConsent\Domain\Repository\MailRepository;
use Madj2k\SimpleConsent\Service\MailService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * class SendCommand
 *
 * Execute on CLI with: 'vendor/bin/typo3 simpleConsent:send'
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_SimpleConsent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class SendCommand extends Command
{

    /**
     * @var \Madj2k\SimpleConsent\Domain\Repository\MailRepository|null
     */
    protected ?MailRepository $mailRepository = null;

    /**
     * @var \Madj2k\Postmaster\Mail\MailMessage|null
     */
    protected ?MailMessage $mailMessage = null;

    /**
     * @var \Madj2k\SimpleConsent\Service\MailService|null
     */
    protected ?MailService $mailService = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager|null
     */
    protected ?PersistenceManager $persistenceManager = null;

    /**
     * @var \TYPO3\CMS\Core\Log\Logger|null
     */
    protected ?Logger $logger = null;


    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure(): void
    {
        $this->setDescription('Creates mails for all relevant recipients to opt in.')
            ->addOption(
                'recipientsLimit',
                'r',
                InputOption::VALUE_REQUIRED,
                'Maximum number of recipients to process on each call (default: 50)',
                50
            )
            ->addOption(
                'sleep',
                's',
                InputOption::VALUE_REQUIRED,
                'How many seconds the script should sleep after each run (default: 10)',
                10
            );
    }


    /**
     * Initializes the command after the input has been bound and before the input
     * is validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @see \Symfony\Component\Console\Input\InputInterface::bind()
     * @see \Symfony\Component\Console\Input\InputInterface::validate()
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->mailMessage = $objectManager->get(MailMessage::class);
        $this->mailService = $objectManager->get(MailService::class);
        $this->mailRepository = $objectManager->get(MailRepository::class);
        $this->persistenceManager = $objectManager->get(PersistenceManager::class);
    }


    /**
     * Executes the command for showing sys_log entries
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @see \Symfony\Component\Console\Input\InputInterface::bind()
     * @see \Symfony\Component\Console\Input\InputInterface::validate()
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $recipientsLimit = $input->getOption('recipientsLimit');
        $sleep = $input->getOption('sleep');

        $result = 0;


        /** @var \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool */
        $connectionPool = \Madj2k\CoreExtended\Utility\GeneralUtility::makeInstance(ConnectionPool::class);

        // find mail with matching status
        $mails = $this->mailRepository->findByStatus(2);

        /** @var \Madj2k\SimpleConsent\Domain\Model\Mail $mail */
        if ($mails) {

            foreach ($mails as $mail) {
                try {
                    // we load the uid of the addresses via queryBuilder because this way we do not run in performance issues
                    $queryBuilder = $connectionPool->getQueryBuilderForTable('tx_simpleconsent_domain_model_mail');
                    $queryBuilder->getRestrictions()->removeAll();

                    $statement = $queryBuilder->select('addresses')
                        ->from('tx_simpleconsent_domain_model_mail')
                        ->where(
                            $queryBuilder->expr()->eq(
                                'uid',
                                $queryBuilder->createNamedParameter($mail->getUid(), \PDO::PARAM_INT)
                            )
                        )->execute();

                    // is there already a queueMail set?
                    if ($mail->getQueueMail()){
                        $this->mailMessage->setQueueMail($mail->getQueueMail());

                    // if not, create one with active pipelining
                    } else {
                        $this->mailMessage->startPipelining();
                        $queueMail = $this->mailMessage->getQueueMail();
                        /** @todo make this dynamic */
                        $queueMail->setSettingsPid(1);
                        $mail->setQueueMail($queueMail);
                    }

                    $recipientCnt = 0;
                    if ($addressesList = $statement->fetchColumn()) {

                        // now load the addresses
                        $addressStatus = ($mail->getReminder() ? 1 : 0);
                        $queryBuilder = $connectionPool->getQueryBuilderForTable('tx_simpleconsent_domain_model_address');
                        $statement = $queryBuilder->select('*')
                            ->from('tx_simpleconsent_domain_model_address')
                            ->where(
                                $queryBuilder->expr()->eq(
                                    'status',
                                    $queryBuilder->createNamedParameter($addressStatus, \PDO::PARAM_INT)
                                ),
                                $queryBuilder->expr()->in(
                                    'uid',
                                    $queryBuilder->createNamedParameter(
                                        \Madj2k\CoreExtended\Utility\GeneralUtility::intExplode(',', $addressesList, true),
                                        Connection::PARAM_INT_ARRAY
                                    )
                                )
                            )
                            ->setMaxResults($recipientsLimit)
                            ->execute();

                        // Do something with that single row
                        while ($address = $statement->fetchAssociative()) {

                            // send mail
                            $this->mailService->sendConsentEmail($address, $mail, $this->mailMessage);

                            // update status
                            $updateQueryBuilder = $connectionPool->getQueryBuilderForTable('tx_simpleconsent_domain_model_address');
                            $updateQueryBuilder->update('tx_simpleconsent_domain_model_address')
                                ->set('status', 1)
                                ->set('tstamp', time())
                                ->where(
                                    $updateQueryBuilder->expr()->eq(
                                        'uid',
                                        $updateQueryBuilder->createNamedParameter($address['uid']), \PDO::PARAM_INT
                                    )
                                );
                            $updateQueryBuilder->execute();

                            // increase counter
                            $recipientCnt++;
                        }
                    }

                    // if there are no recipients left, we stop the pipelining and set the status of the mail accordingly
                    if ($recipientCnt == 0) {
                        $this->mailMessage->stopPipelining();
                        $mail->setStatus(3);

                        $message = sprintf('Mailing finished for consent-mail with uid %s.', $mail->getUid());
                        $io->note($message);
                        $this->getLogger()->log(LogLevel::INFO, $message);

                    } else {

                        $message = sprintf('Sent consent-mail with uid %s to %s recipients', $mail->getUid(), $recipientCnt);
                        $io->note($message);
                        $this->getLogger()->log(LogLevel::INFO, $message);
                    }

                    // sleep defined time
                    usleep(intval($sleep * 1000000));

                } catch (\Exception $e) {

                    $message = sprintf('An error occurred while trying to send consent-mail with uid %s: %s',
                        $mail->getUid(),
                        str_replace(array("\n", "\r"), '', $e->getMessage())
                    );

                    $mail->setStatus(99);

                    // @extensionScannerIgnoreLine
                    $io->error($message);
                    $this->getLogger()->log(LogLevel::ERROR, $message);
                    $result = 1;
                }

                // do update
                $this->mailRepository->update($mail);
                $this->persistenceManager->persistAll();
            }


        } else {

            $message = 'No consent-mails to send.';
            $io->note($message);
            $this->getLogger()->log(LogLevel::INFO, $message);
        }

        $io->writeln('Done');
        return $result;
    }


    /**
     * Returns logger instance
     *
     * @return \TYPO3\CMS\Core\Log\Logger
     */
    protected function getLogger(): Logger
    {
        if (!$this->logger instanceof Logger) {
            $this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
        }

        return $this->logger;
    }
}
