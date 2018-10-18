<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Address;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCustomerClientInterface;

class AddressHandler implements AddressHandlerInterface
{
    protected const FIELD_SALUTATION = 'salutation';
    protected const FIELD_FIRST_NAME = 'first_name';
    protected const FIELD_LAST_NAME = 'last_name';
    protected const FIELD_COMPANY = 'company';
    protected const FIELD_ADDRESS_1 = 'address1';
    protected const FIELD_ADDRESS_2 = 'address2';
    protected const FIELD_ADDRESS_3 = 'address3';
    protected const FIELD_ZIP_CODE = 'zip_code';
    protected const FIELD_CITY = 'city';
    protected const FIELD_ISO2_CODE = 'iso2_code';
    protected const FIELD_PHONE = 'phone';
    protected const FIELD_ID_CUSTOMER_ADDRESS = 'id_customer_address';

    protected const FIELD_IS_DEFAULT_BILLING = 'is_default_billing';
    protected const FIELD_IS_DEFAULT_SHIPPING = 'is_default_shipping';

    protected const FIELDS_ADDRESS_DATA = [
        self::FIELD_SALUTATION,
        self::FIELD_FIRST_NAME,
        self::FIELD_LAST_NAME,
        self::FIELD_COMPANY,
        self::FIELD_ADDRESS_1,
        self::FIELD_ADDRESS_2,
        self::FIELD_ADDRESS_3,
        self::FIELD_ZIP_CODE,
        self::FIELD_CITY,
        self::FIELD_ISO2_CODE,
        self::FIELD_PHONE,
        self::FIELD_ID_CUSTOMER_ADDRESS,
    ];

    protected const FIELD_ADDRESS_FULL_ADDRESS = 'full_address';
    protected const FIELD_ADDRESS_DEFAULT = 'default';
    protected const FIELD_ADDRESS_OPTION_GROUP = 'option_group';

    protected const FIELD_VALUE_COMPANY_BUSINESS_UNIT_SALUTATION = 'Mr';
    protected const FIELD_VALUE_COMPANY_BUSINESS_UNIT_FIRST_NAME = '';
    protected const FIELD_VALUE_COMPANY_BUSINESS_UNIT_LAST_NAME = '';

    protected const FORM_TYPE_OPTION_BILLING_ADDRESS = 'billingAddress';
    protected const FORM_TYPE_OPTION_SHIPPING_ADDRESS = 'shippingAddress';

    protected const KEY_ID_CUSTOMER_ADDRESS = 'addressesForm[%s][' . self::FIELD_ID_CUSTOMER_ADDRESS . ']';
    protected const KEY_FULL_ADDRESS = 'addressesForm[%s][' . self::FIELD_ADDRESS_FULL_ADDRESS . ']';
    protected const KEY_ADDRESS_DEFAULT = 'addressesForm[%s][' . self::FIELD_ADDRESS_DEFAULT . ']';
    protected const KEY_ADDRESS_OPTION_GROUP = 'addressesForm[%s][' . self::FIELD_ADDRESS_OPTION_GROUP . ']';

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
        return ($this->getStoredCompanyBusinessUnitAddresses()->count() > 0);
    }

    /**
     * @param string $formType
     *
     * @return string|null
     */
    public function getCombinedAddressesListJson(string $formType): ?string
    {
        $customerAddressesArray = $this->getCustomerAddressesList($formType);
        $companyBusinessUnitAddressesArray = $this->getCompanyBusinessUnitAddressesList($formType);

        $defaultCustomerAddressIndexes = $this->getAddressListDefaultItemIndexes($customerAddressesArray, $formType);
        $defaultCompanyBusinessUnitAddressIndexes = $this->getAddressListDefaultItemIndexes($companyBusinessUnitAddressesArray, $formType);

        if ((count($defaultCustomerAddressIndexes) > 0 && count($defaultCompanyBusinessUnitAddressIndexes) > 0)
            || (count($defaultCustomerAddressIndexes) === 0 && count($defaultCompanyBusinessUnitAddressIndexes) === 0)
        ) {
            $companyBusinessUnitAddressesArray = $this->resetAddressListDefaultItemValues(
                $companyBusinessUnitAddressesArray,
                $defaultCompanyBusinessUnitAddressIndexes,
                $formType
            );
        }

        return $this->encodeAddressesToJson(
            array_merge(
                $customerAddressesArray,
                $companyBusinessUnitAddressesArray
            )
        );
    }

    /**
     * @param string $formType
     *
     * @return array
     */
    public function getCombinedFullAddressesList(string $formType): array
    {
        $customerAddresses = $this->getCustomerAddressesList($formType);
        $companyBusinessUnitAddresses = $this->getCompanyBusinessUnitAddressesList($formType);

        return array_merge(
            $this->getFullAddressesFromList(
                $customerAddresses,
                $formType,
                static::GLOSSARY_KEY_CUSTOMER_ADDRESS_OPTION_GROUP
            ),
            $this->getFullAddressesFromList(
                $companyBusinessUnitAddresses,
                $formType,
                static::GLOSSARY_KEY_COMPANY_BUSINESS_UNIT_ADDRESS_OPTION_GROUP
            )
        );
    }

    /**
     * @param string $formType
     *
     * @return array
     */
    protected function getCustomerAddressesList(string $formType): array
    {
        $customerTransfer = $this->customerClient->getCustomer();

        $customerAddresses = $customerTransfer->getAddresses();
        if ($customerAddresses === null || $customerAddresses->getAddresses()->count() === 0) {
            return [];
        }

        $customerAddressesArray = [];
        foreach ($customerAddresses->getAddresses() as $addressTransfer) {
            $customerAddressesArray[] = $this->prepareAddressListItemData($addressTransfer->toArray(), $formType);
        }

        return $customerAddressesArray;
    }

    /**
     * @param string $formType
     *
     * @return array
     */
    protected function getCompanyBusinessUnitAddressesList(string $formType): array
    {
        $companyBusinessUnitTransfer = $this->findCompanyBusinessUnit();
        if ($companyBusinessUnitTransfer === null) {
            return [];
        }

        $companyBusinessUnitAddresses = $this->getStoredCompanyBusinessUnitAddresses();
        if ($companyBusinessUnitAddresses->count() === 0) {
            return [];
        }

        $companyBusinessUnitAddressesArray = [];
        foreach ($companyBusinessUnitAddresses as $companyUnitAddressTransfer) {
            $companyUnitAddressArray = $this->prepareCompanyBusinessUnitAddressListItem(
                $companyUnitAddressTransfer,
                $companyBusinessUnitTransfer,
                $formType
            );

            $companyBusinessUnitAddressesArray[] = $companyUnitAddressArray;
        }

        return $companyBusinessUnitAddressesArray;
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\CompanyUnitAddressTransfer[]
     */
    protected function getStoredCompanyBusinessUnitAddresses(): ArrayObject
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
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param string $formType
     *
     * @return array
     */
    protected function prepareCompanyBusinessUnitAddressListItem(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        string $formType
    ): array {
        $companyUnitAddressArray = $companyUnitAddressTransfer->toArray();

        $companyUnitAddressArray = $this->expandAddressListItemWithPersonalInfo($companyUnitAddressArray);
        $companyUnitAddressArray = $this->expandAddressListItemWithCompanyName(
            $companyUnitAddressArray,
            $companyBusinessUnitTransfer->getCompany()
        );

        return $this->prepareAddressListItemData($companyUnitAddressArray, $formType);
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
     *
     * @return array
     */
    protected function expandAddressListItemWithPersonalInfo(array $addressData): array
    {
        return array_merge($addressData, [
            static::FIELD_SALUTATION => static::FIELD_VALUE_COMPANY_BUSINESS_UNIT_SALUTATION,
            static::FIELD_FIRST_NAME => static::FIELD_VALUE_COMPANY_BUSINESS_UNIT_FIRST_NAME,
            static::FIELD_LAST_NAME => static::FIELD_VALUE_COMPANY_BUSINESS_UNIT_LAST_NAME,
        ]);
    }

    /**
     * @param array $addressData
     * @param string $formType
     *
     * @return array
     */
    protected function prepareAddressListItemData(array $addressData, string $formType): array
    {
        $preparedAddressData = [];

        foreach ($addressData as $key => $value) {
            if (in_array($key, static::FIELDS_ADDRESS_DATA, true)) {
                $preparedAddressData[$this->addFormTypePrefix($key, $formType)] = $value;
            }
        }

        $preparedAddressData = $this->addFullAddressValue($addressData, $preparedAddressData, $formType);
        $preparedAddressData = $this->addDefaultAddressValue($addressData, $preparedAddressData, $formType);

        ksort($preparedAddressData);

        return $preparedAddressData;
    }

    /**
     * @param array $addressesArray
     * @param string $formType
     *
     * @return int[]
     */
    protected function getAddressListDefaultItemIndexes(array $addressesArray, string $formType): array
    {
        $index = 0;
        $defaultAddressIndexes = [];
        foreach ($addressesArray as $addressItem) {
            if ($addressItem[sprintf(static::KEY_ADDRESS_DEFAULT, $formType)] === true) {
                $defaultAddressIndexes[] = $index;
            }

            $index++;
        }

        return $defaultAddressIndexes;
    }

    /**
     * @param array $addressesArray
     * @param int[] $addressIndexes
     * @param string $formType
     *
     * @return array
     */
    protected function resetAddressListDefaultItemValues(array $addressesArray, array $addressIndexes, string $formType): array
    {
        foreach ($addressIndexes as $addressIndex) {
            $addressesArray[$addressIndex][sprintf(static::KEY_ADDRESS_DEFAULT, $formType)] = false;
        }

        return $addressesArray;
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
        $defaultAddressKey = sprintf(static::KEY_ADDRESS_DEFAULT, $formType);

        if ($formType === static::FORM_TYPE_OPTION_BILLING_ADDRESS && isset($addressData[static::FIELD_IS_DEFAULT_BILLING])) {
            $preparedAddressData[$defaultAddressKey] = $addressData[static::FIELD_IS_DEFAULT_BILLING] === true;

            return $preparedAddressData;
        }

        if ($formType === static::FORM_TYPE_OPTION_SHIPPING_ADDRESS && isset($addressData[static::FIELD_IS_DEFAULT_SHIPPING])) {
            $preparedAddressData[$defaultAddressKey] = $addressData[static::FIELD_IS_DEFAULT_SHIPPING] === true;

            return $preparedAddressData;
        }

        $preparedAddressData[$defaultAddressKey] = false;

        return $preparedAddressData;
    }

    /**
     * @param array $addressesArray
     * @param string $formType
     * @param string $addressOptionGroup
     *
     * @return array
     */
    protected function getFullAddressesFromList(array $addressesArray, string $formType, string $addressOptionGroup): array
    {
        $fullAddressKey = sprintf(static::KEY_FULL_ADDRESS, $formType);
        $idCustomerAddressKey = sprintf(static::KEY_ID_CUSTOMER_ADDRESS, $formType);
        $addressOptionGroupKey = sprintf(static::KEY_ADDRESS_OPTION_GROUP, $formType);
        $defaultAddressKey = sprintf(static::KEY_ADDRESS_DEFAULT, $formType);

        $fullAddresses = [];
        foreach ($addressesArray as $addressItem) {
            $fullAddressItem = [
                $fullAddressKey => $addressItem[$fullAddressKey],
            ];

            if (isset($addressItem[$idCustomerAddressKey])) {
                $fullAddressItem[$idCustomerAddressKey] = $addressItem[$fullAddressKey];
            }

            $fullAddressItem[$addressOptionGroupKey] = $addressOptionGroup;
            $fullAddressItem[$defaultAddressKey] = $addressItem[$defaultAddressKey];

            $fullAddresses[] = $fullAddressItem;
        }

        return $fullAddresses;
    }

    /**
     * @param array $addressData
     * @param array $preparedAddressData
     * @param string $formType
     *
     * @return array
     */
    protected function addFullAddressValue(array $addressData, array $preparedAddressData, string $formType): array
    {
        $addressFullNameKey = $this->addFormTypePrefix(static::FIELD_ADDRESS_FULL_ADDRESS, $formType);
        $preparedAddressData[$addressFullNameKey] = $this->getFullAddress($addressData);

        return $preparedAddressData;
    }

    /**
     * @param array $addressFormData
     *
     * @return string
     */
    protected function getFullAddress(array $addressFormData): string
    {
        return sprintf(
            '%s %s %s, %s %s, %s %s',
            $addressFormData[static::FIELD_SALUTATION],
            $addressFormData[static::FIELD_FIRST_NAME],
            $addressFormData[static::FIELD_LAST_NAME],
            $addressFormData[static::FIELD_ADDRESS_1],
            $addressFormData[static::FIELD_ADDRESS_2],
            $addressFormData[static::FIELD_ZIP_CODE],
            $addressFormData[static::FIELD_CITY]
        );
    }

    /**
     * @param string $fieldName
     * @param string $formType
     *
     * @return string
     */
    protected function addFormTypePrefix(string $fieldName, string $formType): string
    {
        return sprintf('addressesForm[%s][%s]', $formType, $fieldName);
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
