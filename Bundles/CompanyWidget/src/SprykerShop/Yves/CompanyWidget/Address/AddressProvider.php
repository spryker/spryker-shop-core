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
    public function companyBusinessUnitAddressesExists(): bool
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
            $companyBusinessUnitAddressesList = $this->resetListItemsDefaultStatus(
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
     * @param array $addressListItem
     * @param string $formType
     *
     * @return array
     */
    protected function prepareAddressListItemData(array $addressListItem, string $formType): array
    {
        $preparedAddressListItem = [
            static::FIELD_ADDRESS_HASH => $this->getAddressHash($addressListItem),
        ];
        $preparedAddressListItem = array_merge(
            $addressListItem,
            $this->addDefaultAddressValue($addressListItem, $preparedAddressListItem, $formType)
        );

        ksort($preparedAddressListItem);

        return $preparedAddressListItem;
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
            $this->getFullAddressesList($customerAddressesList, static::GLOSSARY_KEY_CUSTOMER_ADDRESS_OPTION_GROUP),
            $this->getFullAddressesList($companyBusinessUnitAddressesList, static::GLOSSARY_KEY_COMPANY_BUSINESS_UNIT_ADDRESS_OPTION_GROUP)
        );
    }

    /**
     * @param array $addressesList
     * @param string $addressOptionGroup
     *
     * @return array
     */
    protected function getFullAddressesList(array $addressesList, string $addressOptionGroup): array
    {
        $fullAddressesList = [];
        foreach ($addressesList as $addressItem) {
            $fullAddressListItem = [];

            if (isset($addressItem[static::FIELD_ID_CUSTOMER_ADDRESS])) {
                $fullAddressListItem[static::FIELD_ID_CUSTOMER_ADDRESS] = $addressItem[static::FIELD_ID_CUSTOMER_ADDRESS];
            }

            $fullAddressListItem[static::FIELD_OPTION_GROUP] = $addressOptionGroup;
            $fullAddressListItem[static::FIELD_DEFAULT] = $addressItem[static::FIELD_DEFAULT];
            $fullAddressListItem[static::FIELD_ADDRESS_HASH] = $addressItem[static::FIELD_ADDRESS_HASH];

            $fullAddressesList[] = $fullAddressListItem;
        }

        return $fullAddressesList;
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
     * @param array $addressesListItem
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return array
     */
    protected function expandAddressListItemWithCompanyName(array $addressesListItem, CompanyTransfer $companyTransfer): array
    {
        return array_merge($addressesListItem, [
            static::FIELD_COMPANY => $companyTransfer->getName(),
        ]);
    }

    /**
     * @param array $addressListItem
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return array
     */
    protected function expandAddressListItemWithCompanyBusinessUnitName(
        array $addressListItem,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): array {
        return array_merge($addressListItem, [
            static::FIELD_COMPANY_BUSINESS_UNIT_NAME => $companyBusinessUnitTransfer->getName(),
        ]);
    }

    /**
     * @param array $addressListItem
     * @param array $preparedAddressListItem
     * @param string $formType
     *
     * @return array
     */
    protected function addDefaultAddressValue(array $addressListItem, array $preparedAddressListItem, string $formType): array
    {
        if ($formType === static::FORM_TYPE_OPTION_BILLING_ADDRESS && isset($addressListItem[static::FIELD_IS_DEFAULT_BILLING])) {
            $preparedAddressListItem[static::FIELD_DEFAULT] = $addressListItem[static::FIELD_IS_DEFAULT_BILLING] === true;

            return $preparedAddressListItem;
        }

        if ($formType === static::FORM_TYPE_OPTION_SHIPPING_ADDRESS && isset($addressListItem[static::FIELD_IS_DEFAULT_SHIPPING])) {
            $preparedAddressListItem[static::FIELD_DEFAULT] = $addressListItem[static::FIELD_IS_DEFAULT_SHIPPING] === true;

            return $preparedAddressListItem;
        }

        $preparedAddressListItem[static::FIELD_DEFAULT] = false;

        return $preparedAddressListItem;
    }

    /**
     * @param array $addressesList
     *
     * @return int[]
     */
    protected function getAddressListDefaultItemIndexes(array $addressesList): array
    {
        $index = 0;
        $defaultAddressIndexes = [];
        foreach ($addressesList as $addressItem) {
            if ($addressItem[static::FIELD_DEFAULT] === true) {
                $defaultAddressIndexes[] = $index;
            }

            $index++;
        }

        return $defaultAddressIndexes;
    }

    /**
     * @param array $addressesList
     * @param int[] $addressIndexes
     *
     * @return array
     */
    protected function resetListItemsDefaultStatus(array $addressesList, array $addressIndexes): array
    {
        foreach ($addressIndexes as $addressIndex) {
            $addressesList[$addressIndex][static::FIELD_DEFAULT] = false;
        }

        return $addressesList;
    }

    /**
     * @param array $addressListItem
     *
     * @return string
     */
    protected function getAddressHash(array $addressListItem): string
    {
        return crc32(json_encode($addressListItem));
    }

    /**
     * @param array $addressesList
     *
     * @return string|null
     */
    protected function encodeAddressesToJson(array $addressesList): ?string
    {
        $jsonEncodedAddresses = json_encode($addressesList, JSON_PRETTY_PRINT);

        return ($jsonEncodedAddresses !== false)
            ? $jsonEncodedAddresses
            : null;
    }
}
