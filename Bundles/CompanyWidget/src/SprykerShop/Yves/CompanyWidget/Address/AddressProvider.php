<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Address;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCustomerClientInterface;

class AddressProvider implements AddressProviderInterface
{
    protected const FIELD_COMPANY = 'company';
    protected const FIELD_COMPANY_BUSINESS_UNIT_NAME = 'name';

    protected const FIELD_IS_DEFAULT_BILLING = 'is_default_billing';
    protected const FIELD_IS_DEFAULT_SHIPPING = 'is_default_shipping';

    protected const FIELD_DEFAULT = 'default';
    protected const FIELD_OPTION_GROUP = 'option_group';
    protected const FIELD_ID_CUSTOMER_ADDRESS = 'id_customer_address';
    protected const FIELD_ADDRESS_HASH = 'address_hash';

    protected const FORM_TYPE_OPTION_BILLING_ADDRESS = 'billingAddress';
    protected const FORM_TYPE_OPTION_SHIPPING_ADDRESS = 'shippingAddress';

    protected const GLOSSARY_KEY_CUSTOMER_ADDRESS_OPTION_GROUP = 'page.checkout.address.option_group.customer';
    protected const GLOSSARY_KEY_COMPANY_BUSINESS_UNIT_ADDRESS_OPTION_GROUP = 'page.checkout.address.option_group.company_business_unit';

    /**
     * @var \SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCustomerClientInterface $customerClient
     */
    public function __construct(CompanyWidgetToCustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return ($this->getCompanyBusinessUnitAddressCollection()->count() > 0);
    }

    /**
     * @param string $formType
     *
     * @return string|null
     */
    public function getCombinedAddressesListJson(string $formType): ?string
    {
        $customerAddressesList = $this->getCustomerAddressesList();
        $companyBusinessUnitAddressesList = $this->getCompanyBusinessUnitAddressesList();

        $customerAddressesList = $this->prepareCustomerAddressesList($customerAddressesList, $formType);
        $companyBusinessUnitAddressesList = $this->prepareCompanyBusinessUnitAddressList($companyBusinessUnitAddressesList, $formType);

        $defaultCustomerAddressIndexes = $this->getAddressListDefaultItemIndexes($customerAddressesList);
        $defaultCompanyBusinessUnitAddressIndexes = $this->getAddressListDefaultItemIndexes($companyBusinessUnitAddressesList);

        if ((count($defaultCustomerAddressIndexes) > 0 && count($defaultCompanyBusinessUnitAddressIndexes) > 0)
            || (count($defaultCustomerAddressIndexes) === 0 && count($defaultCompanyBusinessUnitAddressIndexes) === 0)
        ) {
            $companyBusinessUnitAddressesList = $this->resetAddressListDefaultItemValues(
                $companyBusinessUnitAddressesList,
                $defaultCompanyBusinessUnitAddressIndexes
            );
        }

        return $this->encodeAddressesToJson(
            array_merge(
                $customerAddressesList,
                $companyBusinessUnitAddressesList
            )
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\AddressTransfer[] $customerAddressesList
     * @param string $formType
     *
     * @return array
     */
    protected function prepareCustomerAddressesList(array $customerAddressesList, string $formType): array
    {
        $preparedCustomerAddressesList = [];
        foreach ($customerAddressesList as $customerAddressListItem) {
            $preparedCustomerAddressesList[] = $this->prepareAddressListItemData($customerAddressListItem->toArray(), $formType);
        }

        return $preparedCustomerAddressesList;
    }

    /**
     * @param array $companyBusinessUnitAddressesList
     * @param string $formType
     *
     * @return array
     */
    protected function prepareCompanyBusinessUnitAddressList(array $companyBusinessUnitAddressesList, string $formType): array
    {
        $companyBusinessUnitTransfer = $this->findCompanyBusinessUnit();
        if ($companyBusinessUnitTransfer === null) {
            return [];
        }

        $preparedCompanyBusinessUnitAddressesList = [];
        foreach ($companyBusinessUnitAddressesList as $companyBusinessUnitAddressesListItem) {
            $preparedCompanyBusinessUnitAddressesList[] = $this->prepareCompanyBusinessUnitAddressListItem(
                $companyBusinessUnitAddressesListItem->toArray(),
                $companyBusinessUnitTransfer,
                $formType
            );
        }

        return $preparedCompanyBusinessUnitAddressesList;
    }

    /**
     * @param array $addressData
     * @param string $formType
     *
     * @return array
     */
    protected function prepareAddressListItemData(array $addressData, string $formType): array
    {
        $preparedAddressData = [
            static::FIELD_ADDRESS_HASH => $this->getAddressHash($addressData),
        ];
        $preparedAddressData = array_merge(
            $addressData,
            $this->addDefaultAddressValue($addressData, $preparedAddressData, $formType)
        );

        ksort($preparedAddressData);

        return $preparedAddressData;
    }

    /**
     * @param array $companyUnitAddressListItem
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param string $formType
     *
     * @return array
     */
    protected function prepareCompanyBusinessUnitAddressListItem(
        array $companyUnitAddressListItem,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        string $formType
    ): array {
        $companyUnitAddressListItem = $this->expandAddressListItemWithCompanyBusinessUnitName(
            $companyUnitAddressListItem,
            $companyBusinessUnitTransfer
        );
        $companyUnitAddressListItem = $this->expandAddressListItemWithCompanyName(
            $companyUnitAddressListItem,
            $companyBusinessUnitTransfer->getCompany()
        );

        return $this->prepareAddressListItemData($companyUnitAddressListItem, $formType);
    }

    /**
     * @param string $formType
     *
     * @return array
     */
    public function getCombinedFullAddressesList(string $formType): array
    {
        $customerAddressesList = $this->getCustomerAddressesList();
        $companyBusinessUnitAddressesList = $this->getCompanyBusinessUnitAddressesList();

        $customerAddressesList = $this->prepareCustomerAddressesList($customerAddressesList, $formType);
        $companyBusinessUnitAddressesList = $this->prepareCompanyBusinessUnitAddressList($companyBusinessUnitAddressesList, $formType);

        return array_merge(
            $this->getFullAddressesFromList($customerAddressesList, static::GLOSSARY_KEY_CUSTOMER_ADDRESS_OPTION_GROUP),
            $this->getFullAddressesFromList($companyBusinessUnitAddressesList, static::GLOSSARY_KEY_COMPANY_BUSINESS_UNIT_ADDRESS_OPTION_GROUP)
        );
    }

    /**
     * @param array $addressesArray
     * @param string $addressOptionGroup
     *
     * @return array
     */
    protected function getFullAddressesFromList(array $addressesArray, string $addressOptionGroup): array
    {
        $fullAddresses = [];
        foreach ($addressesArray as $addressItem) {
            $fullAddressItem = [];

            if (isset($addressItem[static::FIELD_ID_CUSTOMER_ADDRESS])) {
                $fullAddressItem[static::FIELD_ID_CUSTOMER_ADDRESS] = $addressItem[static::FIELD_ID_CUSTOMER_ADDRESS];
            }

            $fullAddressItem[static::FIELD_OPTION_GROUP] = $addressOptionGroup;
            $fullAddressItem[static::FIELD_DEFAULT] = $addressItem[static::FIELD_DEFAULT];
            $fullAddressItem[static::FIELD_ADDRESS_HASH] = $addressItem[static::FIELD_ADDRESS_HASH];

            $fullAddresses[] = $fullAddressItem;
        }

        return $fullAddresses;
    }

    /**
     * @return array
     */
    protected function getCustomerAddressesList(): array
    {
        $customerTransfer = $this->customerClient->getCustomer();

        return $customerTransfer->getAddresses()
            ->getAddresses()
            ->getArrayCopy();
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null
     */
    protected function findCompanyBusinessUnit(): ?CompanyBusinessUnitTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if ($companyUserTransfer !== null) {
            return $companyUserTransfer->getCompanyBusinessUnit();
        }

        return null;
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\CompanyUnitAddressTransfer[]
     */
    protected function getCompanyBusinessUnitAddressCollection(): ArrayObject
    {
        $companyBusinessUnitTransfer = $this->findCompanyBusinessUnit();
        if ($companyBusinessUnitTransfer === null) {
            return new ArrayObject();
        }

        $companyBusinessUnitAddressCollection = $companyBusinessUnitTransfer->getAddressCollection();
        if ($companyBusinessUnitAddressCollection === null) {
            return new ArrayObject();
        }

        return $companyBusinessUnitAddressCollection->getCompanyUnitAddresses();
    }

    /**
     * @return array
     */
    protected function getCompanyBusinessUnitAddressesList(): array
    {
        $companyBusinessUnitTransfer = $this->findCompanyBusinessUnit();
        if ($companyBusinessUnitTransfer === null) {
            return [];
        }

        $companyBusinessUnitAddresses = $this->getCompanyBusinessUnitAddressCollection();
        if ($companyBusinessUnitAddresses->count() === 0) {
            return [];
        }

        return $companyBusinessUnitAddresses->getArrayCopy();
    }

    /**
     * @param array $addressesFormData
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return array
     */
    protected function expandAddressListItemWithCompanyName(array $addressesFormData, CompanyTransfer $companyTransfer): array
    {
        return array_merge($addressesFormData, [
            static::FIELD_COMPANY => $companyTransfer->getName(),
        ]);
    }

    /**
     * @param array $addressData
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return array
     */
    protected function expandAddressListItemWithCompanyBusinessUnitName(
        array $addressData,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): array {
        return array_merge($addressData, [
            static::FIELD_COMPANY_BUSINESS_UNIT_NAME => $companyBusinessUnitTransfer->getName(),
        ]);
    }

    /**
     * @param array $addressData
     * @param array $preparedAddressData
     * @param string $formType
     *
     * @return array
     */
    protected function addDefaultAddressValue(array $addressData, array $preparedAddressData, string $formType): array
    {
        if ($formType === static::FORM_TYPE_OPTION_BILLING_ADDRESS && isset($addressData[static::FIELD_IS_DEFAULT_BILLING])) {
            $preparedAddressData[static::FIELD_DEFAULT] = $addressData[static::FIELD_IS_DEFAULT_BILLING] === true;

            return $preparedAddressData;
        }

        if ($formType === static::FORM_TYPE_OPTION_SHIPPING_ADDRESS && isset($addressData[static::FIELD_IS_DEFAULT_SHIPPING])) {
            $preparedAddressData[static::FIELD_DEFAULT] = $addressData[static::FIELD_IS_DEFAULT_SHIPPING] === true;

            return $preparedAddressData;
        }

        $preparedAddressData[static::FIELD_DEFAULT] = false;

        return $preparedAddressData;
    }

    /**
     * @param array $addressesArray
     *
     * @return int[]
     */
    protected function getAddressListDefaultItemIndexes(array $addressesArray): array
    {
        $index = 0;
        $defaultAddressIndexes = [];
        foreach ($addressesArray as $addressItem) {
            if ($addressItem[static::FIELD_DEFAULT] === true) {
                $defaultAddressIndexes[] = $index;
            }

            $index++;
        }

        return $defaultAddressIndexes;
    }

    /**
     * @param array $addressesArray
     * @param int[] $addressIndexes
     *
     * @return array
     */
    protected function resetAddressListDefaultItemValues(array $addressesArray, array $addressIndexes): array
    {
        foreach ($addressIndexes as $addressIndex) {
            $addressesArray[$addressIndex][static::FIELD_DEFAULT] = false;
        }

        return $addressesArray;
    }

    /**
     * @param array $addressData
     *
     * @return string
     */
    protected function getAddressHash(array $addressData): string
    {
        return crc32(json_encode($addressData));
    }

    /**
     * @param array $addresses
     *
     * @return string|null
     */
    protected function encodeAddressesToJson(array $addresses): ?string
    {
        $jsonEncodedAddresses = json_encode($addresses, JSON_PRETTY_PRINT);

        return ($jsonEncodedAddresses !== false)
            ? $jsonEncodedAddresses
            : null;
    }
}
