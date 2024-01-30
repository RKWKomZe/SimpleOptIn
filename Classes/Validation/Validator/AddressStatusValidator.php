<?php
namespace Madj2k\SimpleConsent\Validation\Validator;

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

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

/**
 * Class AddressStatusValidator
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_SimpleConsent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class AddressStatusValidator extends AbstractValidator
{
    /**
     * Checks if the given value is a valid integer
     *
     * @param mixed $value The value that should be validated
     */
    public function isValid($value)
    {
        if (! $value instanceof \Madj2k\SimpleConsent\Domain\Model\Address) {
            return;
        }

        if (! in_array($value->getStatus(), [2,3,90])) {

            $this->result->forProperty(lcfirst('status'))->addError(
                new \TYPO3\CMS\Extbase\Error\Error(
                    $this->translateErrorMessage(
                        'validator.error.pleaseMakeDecision',
                        'simple_consent'
                    ), 1706621601
                )
            );
        }
    }
}
