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

use Madj2k\CoreExtended\Utility\ClientUtility;
use Madj2k\CoreExtended\Utility\GeneralUtility;
use Madj2k\SimpleConsent\Domain\Model\Address;
use Madj2k\SimpleConsent\Domain\Repository\AddressRepository;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class ConsentController
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
    protected ?AddressRepository $addressRepository = null;


    /**
     * @param \Madj2k\SimpleConsent\Domain\Repository\AddressRepository addressRepository
     */
    public function injectAddressRepository(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }


    /**
     * A template method for displaying custom error flash messages, or to
     * display no flash message at all on errors. Override this to customize
     * the flash message in your action controller.
     *
     * @return string The flash message or FALSE if no flash message should be set
     */
    protected function getErrorFlashMessage()
    {
        return false;
    }


    /**
     * Gets the current data
     *
     * @param string $hash
     * @param \Madj2k\SimpleConsent\Domain\Model\Address|null $addressNew
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("addressNew")
     */
    public function showAction(string $hash, Address $addressNew = null): void
    {

        $address = $this->addressRepository->findOneByHash($hash);
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

        if ($address->getStatus() >= 10) {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'consentController.error.alreadyConfirmed',
                    'simple_consent'
                ),
                '',
                AbstractMessage::WARNING
            );
        }

        /**
         * @see https://stackoverflow.com/questions/40640638/assign-non-persisted-object-to-view-using-extbase
         */
        $this->view->assignMultiple(
            [
                'address' => $address,
                'addressNew' => $addressNew ?: GeneralUtility::makeInstance(Address::class),
                'hash' => $hash,
            ]
        );
    }


    /**
     * Decision concerning usage of data
     *
     * @param \Madj2k\SimpleConsent\Domain\Model\Address $address
     * @param \Madj2k\SimpleConsent\Domain\Model\Address $addressNew
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\TooDirtyException
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("address")
     * @TYPO3\CMS\Extbase\Annotation\Validate("Madj2k\SimpleConsent\Validation\Validator\AddressStatusValidator", param="addressNew")
     */
    public function decisionAction(Address $address, Address $addressNew) : void
    {
        // Update some fields if they are set!
        $allowedProperties = [
            'gender', 'title', 'firstName', 'lastName',
            'company', 'address', 'zip', 'city', 'phone', 'email'];

        foreach ($allowedProperties as $property) {
            $getter = 'get' . ucFirst($property);
            $setter = 'set' . ucFirst($property);

            if (
                (trim($addressNew->$getter()) != '')
                && !($property == 'gender' && $addressNew->$getter() == 99)
            ){
                $address->$setter(trim($addressNew->$getter()));
                $address->setUpdated(true);
            }
        }

        $address->setStatus($addressNew->getStatus());
        $address->setFeedbackTstamp(time());
        $address->setFeedbackIp(ClientUtility::getIp());

        $this->addressRepository->update($address);

        if ($addressNew->getStatus() == 20) {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'consentController.message.deleted',
                    'simple_consent'
                ),
                '',
                AbstractMessage::OK
            );
        } else {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'consentController.message.confirmed',
                    'simple_consent'
                ),
                '',
                AbstractMessage::OK
            );
        }
    }
}
