<?php
namespace Madj2k\SimpleConsent\Domain\Model;

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

use Madj2k\Postmaster\Domain\Model\QueueMail;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Madj2k\CoreExtended\Serialization\SerializableEntityInterface;
use Madj2k\CoreExtended\Serialization\SerializableEntityTrait;


/**
 * Class Mail
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_SimpleConsent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Mail extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity implements SerializableEntityInterface
{
    use SerializableEntityTrait;

    /**
     * @var int
     */
    protected int $crdate = 0;


    /**
     * @var string
     */
    protected string $subject = '';


    /**
     * @var string
     */
    protected string $textPlain = '';


    /**
     * @var string
     */
    protected string $textHtml = '';


    /**
     * @var int
     */
    protected int $pluginPid = 0;


    /**
     * @var int
     */
    protected int $status = 0;


    /**
     * @var int
     */
    protected int $reminder = 0;


    /**
     * @var int
     */
    protected int $sentTstamp = 0;


    /**
     * @var int
     */
    protected int $reminderTstamp = 0;


    /**
     * @var \Madj2k\Postmaster\Domain\Model\QueueMail|null
     */
    protected ?QueueMail $queueMail = null;


    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Madj2k\SimpleConsent\Domain\Model\Address>|null
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected ?ObjectStorage $addresses = null;


    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }


    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->addresses = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }


    /**
     * Returns tstamp
     *
     * @return int
     */
    public function getCrdate(): int
    {
        if (!$this->crdate) {
            $this->crdate = time();
        }

        return $this->crdate;
    }


    /**
     * Returns subject
     *
     * @return string
     */
    public function getSubject():? string
    {
        return $this->subject;
    }


    /**
     * Returns label
     *
     * @return string
     */
    public function getLabel():? string
    {
        return $this->uid . ' - ' . $this->subject . ' (' . date('d.m.Y H:i', $this->getCrdate()) . ')';
    }


    /**
     * Sets subject
     *
     * @param string $subject
     * @return void
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }


    /**
     * Returns textPlain
     *
     * @return string $textPlain
     */
    public function getTextPlain(): string
    {
        return $this->textPlain;
    }


    /**
     * Returns textPlain
     *
     * @return string $textPlain
     */
    public function getTextPlainFormatted (): string
    {
        return preg_replace('#<br />#', '\n', nl2br($this->textPlain));
    }


    /**
     * Sets textPlain
     *
     * @param string $textPlain
     * @return void
     */
    public function setTextPlain(string $textPlain): void
    {
        $this->textPlain = $textPlain;
    }


    /**
     * Returns textHtml
     *
     * @return string $textHtml
     */
    public function getTextHtml(): string
    {
        return $this->textHtml;
    }


    /**
     * Sets textHtml
     *
     * @param string $textHtml
     * @return void
     */
    public function setTextHtml(string $textHtml): void
    {
        $this->textHtml = $textHtml;
    }


    /**
     * Returns pluginPid
     *
     * @return int
     */
    public function getPluginPid(): int
    {
        return $this->pluginPid;
    }


    /**
     * Sets pluginPid
     *
     * @param int $pluginPid
     * @return void
     */
    public function setPluginPid(int $pluginPid): void
    {
        $this->pluginPid = $pluginPid;
    }


    /**
     * Returns status
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }


    /**
     * Sets status
     *
     * @param int $status
     * @return void
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }


    /**
     * Returns reminder
     *
     * @return string
     */
    public function getReminder(): string
    {
        return $this->reminder;
    }


    /**
     * Sets reminder
     *
     * @param int $reminder
     * @return void
     */
    public function setReminder(int $reminder): void
    {
        $this->reminder = $reminder;
    }


    /**
     * Returns sentTstamp
     *
     * @return string
     */
    public function getSentTstamp(): string
    {
        return $this->sentTstamp;
    }


    /**
     * Sets sentTstamp
     *
     * @param int $sentTstamp
     * @return void
     */
    public function setSentTstamp(int $sentTstamp): void
    {
        $this->sentTstamp = $sentTstamp;
    }


    /**
     * Returns reminderTstamp
     *
     * @return string
     */
    public function getReminderTstamp(): string
    {
        return $this->reminderTstamp;
    }


    /**
     * Sets reminderTstamp
     *
     * @param int $reminderTstamp
     * @return void
     */
    public function setReminderTstamp(int $reminderTstamp): void
    {
        $this->reminderTstamp = $reminderTstamp;
    }


    /**
     * Returns queueMail
     *
     * @return \Madj2k\Postmaster\Domain\Model\QueueMail $queueMail
     */
    public function getQueueMail(): ?QueueMail
    {
        return $this->queueMail;
    }


    /**
     * Unsets queueMail
     *
     * @return void
     */
    public function unsetQueueMail(): void
    {
        $this->queueMail = null;
    }


    /**
     * Sets queueMail
     *
     * @param \Madj2k\Postmaster\Domain\Model\QueueMail $queueMail
     * @return void
     */
    public function setQueueMail(QueueMail $queueMail): void
    {
        $this->queueMail = $queueMail;
    }


    /**
     * Returns the addresses
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Madj2k\SimpleConsent\Domain\Model\Address> $addresses
     */
    public function getAddresses(): ObjectStorage
    {
        return $this->addresses;
    }

    /**
     * Sets the addresses
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Madj2k\SimpleConsent\Domain\Model\Address> $addresses
     * @return void
     */
    public function setAddresses(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $addresses)
    {
        $this->addresses = $addresses;
    }

    /**
     * Adds a Category
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Address$address
     * @return void
     */
    public function addAddresses(\Madj2k\SimpleConsent\Domain\Model\Address $address): void
    {
        $this->addresses->attach($address);
    }

    /**
     * Removes a Category
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Address $addressesToRemove
     * @return void
     */
    public function removeAddresses(\Madj2k\SimpleConsent\Domain\Model\Address $addressToRemove): void
    {
        $this->addresses->detach($addressToRemove);
    }


}
