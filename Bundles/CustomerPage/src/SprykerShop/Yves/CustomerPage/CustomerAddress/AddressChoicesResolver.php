<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\CustomerAddress;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm;

class AddressChoicesResolver implements AddressChoicesResolverInterface
{
    /**
     * @var string
     */
    protected const ADDRESS_LABEL_PATTERN = '%s %s %s, %s %s, %s %s';

    /**
     * @var string
     */
    protected const SANITIZED_CUSTOMER_ADDRESS_LABEL_PATTERN = '%s - %s';

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return array<string, string|int>
     */
    public function getAddressChoices(?CustomerTransfer $customerTransfer): array
    {
        $choices = $this->getDefaultAddressChoices();
        if ($customerTransfer === null) {
            return $choices;
        }

        $customerAddressesTransfer = $customerTransfer->getAddresses();

        if (!$this->isCustomerHasAddress($customerAddressesTransfer)) {
            return $choices;
        }

        $choices = $this->addCustomerAddressChoices($customerAddressesTransfer->getAddresses(), $choices);

        return $this->sanitizeDuplicatedCustomerAddressChoices($choices);
    }

    /**
     * @return array<string, string>
     */
    protected function getDefaultAddressChoices(): array
    {
        return [
            CheckoutAddressForm::GLOSSARY_KEY_ACCOUNT_ADD_NEW_ADDRESS => CheckoutAddressForm::VALUE_ADD_NEW_ADDRESS,
        ];
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\AddressTransfer>|iterable<\Generated\Shared\Transfer\AddressTransfer> $customerAddressesCollection
     * @param array<string, string> $choices
     *
     * @return array<string, string|int>
     */
    protected function addCustomerAddressChoices(iterable $customerAddressesCollection, array $choices = []): array
    {
        foreach ($customerAddressesCollection as $addressTransfer) {
            $idCustomerAddress = $addressTransfer->getIdCustomerAddress();
            if ($idCustomerAddress === null) {
                continue;
            }

            $choices[$this->getAddressLabel($addressTransfer)] = $idCustomerAddress;
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
            $addressTransfer->getCity(),
        );
    }

    /**
     * @param iterable<string, string|int> $choices
     *
     * @return array<string, string|int>
     */
    protected function sanitizeDuplicatedCustomerAddressChoices(iterable $choices): array
    {
        $sanitizedChoices = [];
        $choicesCounts = [];

        foreach ($choices as $addressLabel => $idAddress) {
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

        asort($sanitizedChoices, SORT_NATURAL);

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
     * @param array<string, string|int> $customerAddressChoices
     * @param bool $canDeliverToMultipleShippingAddresses
     *
     * @return array<string, string|int>
     */
    public function getSingleShippingAddressChoices(array $customerAddressChoices, bool $canDeliverToMultipleShippingAddresses): array
    {
        if ($canDeliverToMultipleShippingAddresses === false) {
            return $customerAddressChoices;
        }

        $customerAddressChoices[CheckoutAddressForm::GLOSSARY_KEY_DELIVER_TO_MULTIPLE_ADDRESSES] = CheckoutAddressForm::VALUE_DELIVER_TO_MULTIPLE_ADDRESSES;

        return $customerAddressChoices;
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
