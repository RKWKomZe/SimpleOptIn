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

use Madj2k\CoreExtended\Utility\GeneralUtility;
use Madj2k\SimpleConsent\Domain\Model\Address;
use Madj2k\SimpleConsent\Domain\Repository\AddressRepository;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class ConsentoCntroller
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_SimpleConsent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ConsentController extends ActionController
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

        if ($hash == 'abcdefghijklmnopqrstuvwxyz1234') {
            $address = GeneralUtility::makeInstance(Address::class);
            $address->setTitle('Dr.');
            $address->setGender(3);
            $address->setFirstName('Sam');
            $address->setLastName('Muster');
            $address->setCompany('Muster Inc.');
            $address->setAddress('Testallee 15');
            $address->setZip('12345');
            $address->setCity('Testhausen');
            $address->setPhone('1234 / 123456');
            $address->setEmail('sam@muster.com');
        } else {
            $address = $this->addressRepository->findOneByHash($hash);
        }

        if (! $address) {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'consentController.error.noAddressFound',
                    'simple_consent'
                ),
                '',
                AbstractMessage::ERROR
            );
        }

        if ($address->getStatus() > 1) {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'consentController.error.alreadyConfirmed',
                    'simple_consent'
                ),
                '',
                AbstractMessage::WARNING
            );
        }

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
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function confirmAction(Address $address) : void
    {
        $address->setStatus(2);
        $this->addressRepository->update($address);

        $this->addFlashMessage(
            LocalizationUtility::translate(
                'consentController.message.confirmed',
                'simple_consent'
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
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function deleteAction(Address $address) : void
    {
        $address->setStatus(3);
        $this->addressRepository->update($address);

        $this->addFlashMessage(
            LocalizationUtility::translate(
                'consentController.message.deleted',
                'simple_consent'
            ),
            '',
            AbstractMessage::OK
        );
    }
}
