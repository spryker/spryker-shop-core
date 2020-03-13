<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\CustomerAddress;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class AddressChoicesResolver implements AddressChoicesResolverInterface
{
    protected const ADDRESS_LABEL_PATTERN = '%s %s %s, %s %s, %s %s';
    protected const SANITIZED_CUSTOMER_ADDRESS_LABEL_PATTERN = '%s - %s';

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return string[]
     */
    public function getAddressChoicesForCustomer(?CustomerTransfer $customerTransfer): array
    {
        if ($customerTransfer === null) {
            return [];
        }

        $customerAddressesTransfer = $customerTransfer->getAddresses();

        if (!$this->isCustomerHasAddress($customerAddressesTransfer)) {
            return [];
        }

        $choices = $this->getCustomerAddressChoices($customerAddressesTransfer->getAddresses());

        return $this->sanitizeDuplicatedCustomerAddressChoices($choices);
    }

    /**
     * @param iterable|\ArrayObject|\Generated\Shared\Transfer\AddressTransfer[] $customerAddressesCollection
     *
     * @return string[]
     */
    protected function getCustomerAddressChoices(iterable $customerAddressesCollection): array
    {
        $choices = [];

        foreach ($customerAddressesCollection as $addressTransfer) {
            $idCustomerAddress = $addressTransfer->getIdCustomerAddress();
            if ($idCustomerAddress === null) {
                continue;
            }

            $choices[$idCustomerAddress] = $this->getAddressLabel($addressTransfer);
        }

        return $choices;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function getAddressLabel(AddressTransfer $addressTransfer): string
    {
        return sprintf(
            static::ADDRESS_LABEL_PATTERN,
            $addressTransfer->getSalutation(),
            $addressTransfer->getFirstName(),
            $addressTransfer->getLastName(),
            $addressTransfer->getAddress1(),
            $addressTransfer->getAddress2(),
            $addressTransfer->getZipCode(),
            $addressTransfer->getCity()
        );
    }

    /**
     * @param iterable|string[] $choices
     *
     * @return string[]
     */
    protected function sanitizeDuplicatedCustomerAddressChoices(iterable $choices): array
    {
        $sanitizedChoices = [];
        $choicesCounts = [];

        foreach ($choices as $idAddress => $addressLabel) {
            if (isset($sanitizedChoices[$addressLabel])) {
                $originAddressLabel = $addressLabel;
                if (!isset($choicesCounts[$originAddressLabel])) {
                    $choicesCounts[$originAddressLabel] = 1;
                }

                $addressLabel = $this->getSanitizedCustomerAddressChoices($addressLabel, $choicesCounts[$originAddressLabel]);
                $choicesCounts[$originAddressLabel]++;
            }

            $sanitizedChoices[$addressLabel] = $idAddress;
        }

        ksort($sanitizedChoices, SORT_NATURAL);

        return $sanitizedChoices;
    }

    /**
     * @param string $addressLabel
     * @param int $itemNumber
     *
     * @return string
     */
    protected function getSanitizedCustomerAddressChoices(string $addressLabel, int $itemNumber): string
    {
        return sprintf(static::SANITIZED_CUSTOMER_ADDRESS_LABEL_PATTERN, $addressLabel, $itemNumber);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressesTransfer|null $customerAddressesTransfer
     *
     * @return bool
     */
    protected function isCustomerHasAddress(?AddressesTransfer $customerAddressesTransfer): bool
    {
        return $customerAddressesTransfer !== null && count($customerAddressesTransfer->getAddresses()) !== 0;
    }
}
