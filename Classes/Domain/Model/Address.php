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

/**
 * Class Address
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_SimpleConsent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Address extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var int
     * @TYPO3\CMS\Extbase\Annotation\Validate("RegularExpression", options={ "regularExpression":"/^0|1|2|99$/i" })
     */
    protected int $gender = 99;


    /**
     * @var string
     */
    protected string $title = '';


    /**
     * @var string
     */
    protected string $firstName = '';


    /**
     * @var string
     */
    protected string $lastName = '';


    /**
     * @var string
     */
    protected string $company = '';


    /**
     * @var string
     */
    protected string $address = '';


    /**
     * @var string
     */
    protected string $zip = '';


    /**
     * @var string
     */
    protected string $city = '';


    /**
     * @var string
     */
    protected string $phone = '';


    /**
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("EmailAddress")
     */
    protected string $email = '';


    /**
     * @var string
     */
    protected string $hash = '';


    /**
     * @var int
     */
    protected int $status = 0;


    /**
     * @var int
     */
    protected int $updated = 0;


    /**
     * @var int
     */
    protected int $feedbackTstamp = 0;


    /**
     * @var string
     */
    protected string $feedbackIp = '';


    /**
     * Returns gender
     *
     * @return int $gender
     */
    public function getGender(): int
    {
        return $this->gender;
    }


    /**
     * Sets gender
     *
     * @param int $gender
     * @return void
     */
    public function setGender(int $gender): void
    {
        $this->gender = $gender;
    }


    /**
     * Returns title
     *
     * @return string $title
     */
    public function getTitle():? string
    {
        return $this->title;
    }


    /**
     * Sets title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }


    /**
     * Returns firstName
     *
     * @return string $firstName
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }


    /**
     * Sets firstName
     *
     * @param string $firstName
     * @return void
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }


    /**
     * Returns lastName
     *
     * @return string $lastName
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }


    /**
     * Sets lastName
     *
     * @param string $lastName
     * @return void
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }


    /**
     * Returns company
     *
     * @return string $company
     */
    public function getCompany(): string
    {
        return $this->company;
    }


    /**
     * Sets company
     *
     * @param string $company
     * @return void
     */
    public function setCompany(string $company): void
    {
        $this->company = $company;
    }


    /**
     * Returns address
     *
     * @return string $address
     */
    public function getAddress(): string
    {
        return $this->address;
    }


    /**
     * Sets address
     *
     * @param string $address
     * @return void
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }


    /**
     * Returns zip
     *
     * @return string
     */
    public function getZip(): string
    {
        return $this->zip;
    }


    /**
     * Sets zip
     *
     * @param string $zip
     * @return void
     */
    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }


    /**
     * Returns city
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }


    /**
     * Sets city
     *
     * @param string $city
     * @return void
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * Returns phone
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }


    /**
     * Sets phone
     *
     * @param string $phone
     * @return void
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }


    /**
     * Returns email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }


    /**
     * Sets email
     *
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }


    /**
     * Returns hash
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }


    /**
     * Sets hash
     *
     * @param string $hash
     * @return void
     */
    public function setHash(string $hash): void
    {
        $this->hash = $hash;
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
     * Returns updated
     *
     * @return int
     */
    public function getUpdated(): int
    {
        return $this->updated;
    }


    /**
     * Sets updated
     *
     * @param int $updated
     * @return void
     */
    public function setUpdated(int $updated): void
    {
        $this->updated = $updated;
    }


    /**
     * Returns feedbackTstamp
     *
     * @return int
     */
    public function getFeedbackTstamp(): int
    {
        return $this->feedbackTstamp;
    }


    /**
     * Sets feedbackTstamp
     *
     * @param int $feedbackTstamp
     * @return void
     */
    public function setFeedbackTstamp(int $feedbackTstamp): void
    {
        $this->feedbackTstamp = $feedbackTstamp;
    }


    /**
     * Returns feedbackIp
     *
     * @return string
     */
    public function getFeedbackIp(): string
    {
        return $this->feedbackIp;
    }


    /**
     * Sets feedbackIp
     *
     * @param string $feedbackIp
     * @return void
     */
    public function setFeedbackIp(string $feedbackIp): void
    {
        $this->feedbackIp = $feedbackIp;
    }

}
