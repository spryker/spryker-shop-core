<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Widget\DataProvider;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class CompanyBusinessUnitAddressWidgetDataProvider
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

    protected const FIELD_ADDRESS_FULL_ADDRESS = 'full_address';

    protected const FIELDS_ADDRESSES_FORM = [
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

    /**
     * @param \Generated\Shared\Transfer\AddressesTransfer $addressesTransfer
     * @param string $formType
     *
     * @return array
     */
    public function getCustomerAddresses(AddressesTransfer $addressesTransfer, string $formType): array
    {
        $customerAddressesArray = [];
        foreach ($addressesTransfer->getAddresses() as $addressTransfer) {
            $addressFields = $this->prepareAddressFields(
                $addressTransfer->toArray(),
                $formType
            );

            $addressFullNameKey = $this->addFormTypePrefix(static::FIELD_ADDRESS_FULL_ADDRESS, $formType);
            $addressFields[$addressFullNameKey] = $this->getFullAddress(
                $addressTransfer->toArray()
            );

            $customerAddressesArray[] = $addressFields;
        }

        return $customerAddressesArray;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     * @param string $formType
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    public function getCompanyBusinessUnitAddresses(
        CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer,
        string $formType,
        CustomerTransfer $customerTransfer
    ): array {
        $companyBusinessUnitAddresses = $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses();
        if (empty($companyBusinessUnitAddresses)) {
            return [];
        }

        $companyBusinessUnitAddressesArray = [];
        foreach ($companyBusinessUnitAddresses as $companyUnitAddressTransfer) {
            $addressFields = $this->expandAddressFieldsWithCustomerData(
                $companyUnitAddressTransfer->toArray(),
                $customerTransfer
            );

            $preparedAddressFields = $this->prepareAddressFields($addressFields, $formType);

            $addressFullNameKey = $this->addFormTypePrefix(static::FIELD_ADDRESS_FULL_ADDRESS, $formType);
            $preparedAddressFields[$addressFullNameKey] = $this->getFullAddress($addressFields);

            $companyBusinessUnitAddressesArray[] = $preparedAddressFields;
        }

        return $companyBusinessUnitAddressesArray;
    }

    /**
     * @param array $addressFields
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    protected function expandAddressFieldsWithCustomerData(array $addressFields, CustomerTransfer $customerTransfer): array
    {
        return array_merge($addressFields, [
            static::FIELD_SALUTATION => $customerTransfer->getSalutation(),
            static::FIELD_FIRST_NAME => $customerTransfer->getFirstName(),
            static::FIELD_LAST_NAME => $customerTransfer->getLastName(),
            static::FIELD_COMPANY => $customerTransfer->getCompany(),
        ]);
    }

    /**
     * @param array $fields
     * @param string $formType
     *
     * @return array
     */
    protected function prepareAddressFields(array $fields, string $formType): array
    {
        $preparedFormFields = [];

        foreach ($fields as $key => $value) {
            if (in_array($key, static::FIELDS_ADDRESSES_FORM, true)) {
                $preparedFormFields[$this->addFormTypePrefix($key, $formType)] = $value;
            }
        }

        return $preparedFormFields;
    }

    /**
     * @param string $addressField
     * @param string $formType
     *
     * @return string
     */
    protected function addFormTypePrefix(string $addressField, string $formType): string
    {
        return sprintf('addressesForm[%s][%s]', $formType, $addressField);
    }

    /**
     * @param array $fields
     *
     * @return string
     */
    protected function getFullAddress(array $fields): string
    {
        return sprintf(
            '%s %s %s, %s %s, %s %s',
            $fields[static::FIELD_SALUTATION],
            $fields[static::FIELD_FIRST_NAME],
            $fields[static::FIELD_LAST_NAME],
            $fields[static::FIELD_ADDRESS_1],
            $fields[static::FIELD_ADDRESS_2],
            $fields[static::FIELD_ZIP_CODE],
            $fields[static::FIELD_CITY]
        );
    }
}
