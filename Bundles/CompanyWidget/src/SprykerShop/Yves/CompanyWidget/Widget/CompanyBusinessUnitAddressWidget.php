<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Widget;

use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CompanyWidget\CompanyWidgetFactory getFactory()
 */
class CompanyBusinessUnitAddressWidget extends AbstractWidget
{
    protected const PREFIX_KEY_CUSTOMER_ADDRESS = 'c_';
    protected const PREFIX_KEY_COMPANY_BUSINESS_UNIT_ADDRESS = 'bu_';

    /**
     * @param string $formType
     */
    public function __construct(string $formType)
    {
        $this->addParameter('formType', $formType)
            ->addParameter('isApplicable', $this->isApplicable())
            ->addParameter('addresses', $this->encodeAddressesToJson(
                array_merge(
                    $this->getCustomerAddressesAssociativeArray(),
                    $this->getCompanyBusinessUnitAddressesAssociativeArray()
                )
            ));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CompanyBusinessUnitAddressWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CompanyWidget/views/company-business-unit-address/company-business-unit-address.twig';
    }

    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if ($customerTransfer->getCompanyUserTransfer() === null) {
            return false;
        }

        if (empty($this->getCompanyBusinessUnitAddressesAssociativeArray())) {
            return false;
        }

        return true;
    }

    /**
     * @return array Indexes are customer address prefix + customer id
     */
    protected function getCustomerAddressesAssociativeArray(): array
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $customerAddressesArray = [];
        foreach ($customerTransfer->getAddresses()->getAddresses() as $addressTransfer) {
            $customerAddressKey = static::PREFIX_KEY_CUSTOMER_ADDRESS . $addressTransfer->getFkCustomer();
            $customerAddressesArray[$customerAddressKey] = $addressTransfer->toArray();
        }

        return $customerAddressesArray;
    }

    /**
     * @return array Indexes are company business unit address prefix + address key
     */
    protected function getCompanyBusinessUnitAddressesAssociativeArray(): array
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if ($companyUserTransfer === null) {
            return [];
        }

        $companyBusinessUnit = $companyUserTransfer->getCompanyBusinessUnit();

        $companyBusinessUnitAddressCollection = $companyBusinessUnit->getAddressCollection();
        if ($companyBusinessUnitAddressCollection === null) {
            return [];
        }

        return $this->mapCompanyBusinessUnitAddressesToAssociativeArray($companyBusinessUnitAddressCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     *
     * @return array Indexes are company business unit address prefix + address key
     */
    protected function mapCompanyBusinessUnitAddressesToAssociativeArray(
        CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
    ): array {
        $companyBusinessUnitAddresses = $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses();
        if (empty($companyBusinessUnitAddresses)) {
            return [];
        }

        $companyBusinessUnitAddressesArray = [];
        foreach ($companyBusinessUnitAddresses as $companyUnitAddressTransfer) {
            $companyBusinessUnitAddressesKey = static::PREFIX_KEY_COMPANY_BUSINESS_UNIT_ADDRESS . $companyUnitAddressTransfer->getKey();
            $companyBusinessUnitAddressesArray[$companyBusinessUnitAddressesKey] = $companyUnitAddressTransfer->toArray();
        }

        return $companyBusinessUnitAddressesArray;
    }

    /**
     * @param array $addresses
     *
     * @return string|null
     */
    protected function encodeAddressesToJson(array $addresses): ?string
    {
        $jsonEncodedAddresses = json_encode($addresses);

        return ($jsonEncodedAddresses !== false)
            ? $jsonEncodedAddresses
            : null;
    }
}
