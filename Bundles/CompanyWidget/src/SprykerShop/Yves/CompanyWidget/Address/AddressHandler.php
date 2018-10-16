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

    protected const FIELD_VALUE_COMPANY_BUSINESS_UNIT_SALUTATION = 'Mr';
    protected const FIELD_VALUE_COMPANY_BUSINESS_UNIT_FIRST_NAME = '';
    protected const FIELD_VALUE_COMPANY_BUSINESS_UNIT_LAST_NAME = '';

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
     * @param string $formType
     *
     * @return array
     */
    public function getCustomerAddressesArray(string $formType): array
    {
        $customerTransfer = $this->customerClient->getCustomer();

        $customerAddresses = $customerTransfer->getAddresses();
        if ($customerAddresses === null || $customerAddresses->getAddresses()->count() === 0) {
            return [];
        }

        $customerAddressesArray = [];
        foreach ($customerAddresses->getAddresses() as $addressTransfer) {
            $customerAddressesArray[] = $this->prepareAddressData($addressTransfer->toArray(), $formType);
        }

        return $customerAddressesArray;
    }

    /**
     * @param string $formType
     *
     * @return array
     */
    public function getCompanyBusinessUnitAddressesArray(string $formType): array
    {
        $companyBusinessUnitTransfer = $this->findCompanyBusinessUnit();
        if ($companyBusinessUnitTransfer === null) {
            return [];
        }

        $companyBusinessUnitAddresses = $this->getCompanyBusinessUnitAddresses();
        if ($companyBusinessUnitAddresses->count() === 0) {
            return [];
        }

        $companyBusinessUnitAddressesArray = [];
        foreach ($companyBusinessUnitAddresses as $companyUnitAddressTransfer) {
            $companyUnitAddressArray = $this->prepareCompanyBusinessUnitAddressData(
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
    public function getCompanyBusinessUnitAddresses(): ArrayObject
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
    protected function prepareCompanyBusinessUnitAddressData(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        string $formType
    ): array {
        $companyUnitAddressArray = $companyUnitAddressTransfer->toArray();

        $companyUnitAddressArray = $this->expandAddressDataWithPersonalInfo($companyUnitAddressArray);
        $companyUnitAddressArray = $this->expandAddressDataWithCompanyName(
            $companyUnitAddressArray,
            $companyBusinessUnitTransfer->getCompany()
        );

        return $this->prepareAddressData($companyUnitAddressArray, $formType);
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
    protected function expandAddressDataWithCompanyName(array $addressesFormData, CompanyTransfer $companyTransfer): array
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
    protected function expandAddressDataWithPersonalInfo(array $addressData): array
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
    protected function prepareAddressData(array $addressData, string $formType): array
    {
        $preparedFormFields = [];

        foreach ($addressData as $key => $value) {
            if (in_array($key, static::FIELDS_ADDRESS_DATA, true)) {
                $preparedFormFields[$this->addFormTypePrefixToAddressFieldName($key, $formType)] = $value;
            }
        }

        $addressFullNameKey = $this->addFormTypePrefixToAddressFieldName(static::FIELD_ADDRESS_FULL_ADDRESS, $formType);
        $preparedFormFields[$addressFullNameKey] = $this->getFullAddress($addressData);

        ksort($preparedFormFields);

        return $preparedFormFields;
    }

    /**
     * @param string $fieldName
     * @param string $formType
     *
     * @return string
     */
    protected function addFormTypePrefixToAddressFieldName(string $fieldName, string $formType): string
    {
        return sprintf('addressesForm[%s][%s]', $formType, $fieldName);
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
}
