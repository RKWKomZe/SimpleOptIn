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
 * Class Mail
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_SimpleConsent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Mail extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
   
    /**
     * @var string
     */
    protected string $title = '';


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
    protected int $status = 0;
    

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
     * Returns textPlain
     *
     * @return string $textPlain
     */
    public function getTextPlain(): string
    {
        return $this->textPlain;
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
     * Returns status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }


    /**
     * Sets status
     *
     * @param string $status
     * @return void
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

}
