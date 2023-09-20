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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
     * addressRepository
     *
     * @var \Madj2k\SimpleConsent\Domain\Repository\AddressRepository|null
     */
    protected ?AddressRepository $addressRepository = null;


    /**
     * @var \Madj2k\Postmaster\Mail\MailMessage
     */
    protected MailMessage $mailMessage;


    /**
     * @var \Madj2k\Postmaster\Validation\QueueMailValidator
     */
    protected QueueMailValidator $queueMailValidator;


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
        $this->addressRepository = $objectManager->get(AddressRepository::class);
        $this->mailMessage = $objectManager->get(MailMessage::class);
        $this->queueMailValidator = $objectManager->get(QueueMailValidator::class);
    }


    /**
     * Executes the command for showing sys_log entries
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
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
        try {

            $addresses = $this->addressRepository->findBySent(0);
            if (count($addresses)) {

                /** @var \RKW\RkwNewsletter\Domain\Model\Issue $issue */
                foreach ($issues as $issue) {

                    $this->mailProcessor->setIssue($issue);
                    $this->mailProcessor->setRecipients();
                    $this->mailProcessor->sendMails($recipientsPerNewsletterLimit);

                    usleep(intval($sleep * 1000000));
                }

            } else {
                $message = 'No issues to build.';
                $io->note($message);
                $this->getLogger()->log(LogLevel::INFO, $message);
            }

        } catch (\Exception $e) {

            $message = sprintf('An unexpected error occurred while trying to update the statistics of e-mails: %s',
                str_replace(array("\n", "\r"), '', $e->getMessage())
            );

            $io->error($message);
            $this->getLogger()->log(LogLevel::ERROR, $message);
            $result = 1;
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
