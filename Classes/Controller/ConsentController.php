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

use Madj2k\SimpleConsent\Domain\Model\Address;
use Madj2k\SimpleConsent\Domain\Repository\AddressRepository;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class ConsentoCntroller
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_SimpleConsent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ConsentController extends AbstractController
{

    /**
     * @var \Madj2k\SimpleConsent\Domain\Repository\AddressRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected AddressRepository $addressRepository;


    /**
     * Gets the current data
     *
     * @param string $hash
     * @return void
     */
    public function showAction(string $hash): void
    {
         $address = $this->addressRepository->findByHash($hash);
         $this->view->assignMultiple(
            [
                'address' => $address
            ]
        );
    }


    /**
     * Confirm usage of data
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Address $address
     * @return void
     */
    public function confirmAction(Address $address) : void
    {
        $address->setStatus(2);
        $this->addressRepository->update($address);

        $this->addFlashMessage(
            LocalizationUtility::translate(
                'consentController.message.confirmed',
                'simple_optin'
            ),
            '',
            AbstractMessage::OK
        );
    }


    /**
     * Delete data
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Address $address
     * @return void
     */
    public function deleteAction(Address $address) : void
    {
        $address->setStatus(3);
        $this->addressRepository->update($address);

        $this->addFlashMessage(
            LocalizationUtility::translate(
                'consentController.message.deleted',
                'simple_optin'
            ),
            '',
            AbstractMessage::OK
        );
    }
}
