<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Widget;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\AbstractType;

/**
 * @method \SprykerShop\Yves\CompanyWidget\CompanyWidgetFactory getFactory()
 */
class CompanyBusinessUnitAddressWidget extends AbstractWidget
{
    protected const PREFIX_KEY_CUSTOMER_ADDRESS = 'c_';
    protected const PREFIX_KEY_COMPANY_BUSINESS_UNIT_ADDRESS = 'bu_';

    /**
     * @param \Symfony\Component\Form\AbstractType $formType
     */
    public function __construct(AbstractType $formType)
    {
        $this->addParameter('formType', $formType)
            ->addParameter('isApplicable', $this->getIsApplicable())
            ->addParameter('customerAddresses', $this->encodeAddressesToJson($this->getCustomerAddresses()))
            ->addParameter('businessUnitAddresses', $this->encodeAddressesToJson($this->findCompanyBusinessUnitAddresses()));
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
        return '@CompanyWidget/views/address/address.twig';
    }

    /**
     * @return bool
     */
    public function getIsApplicable(): bool
    {
        if ($this->getCustomer()->getCompanyUserTransfer() === null) {
            return false;
        }

        if (empty($this->findCompanyBusinessUnitAddresses())) {
            return false;
        }

        return true;
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

    /**
     * @return array
     */
    protected function getCustomerAddresses(): array
    {
        $customerAddresses = $this->getCustomer()
            ->getAddresses();

        $customerAddressesArray = [];
        foreach ($customerAddresses->getAddresses() as $addressTransfer) {
            $customerAddressKey = static::PREFIX_KEY_CUSTOMER_ADDRESS . $addressTransfer->getFkCustomer();
            $customerAddressesArray[$customerAddressKey] = $addressTransfer->toArray();
        }

        return $customerAddressesArray;
    }

    /**
     * @return array Indexes are company business unit prefix + company business unit address key
     */
    protected function findCompanyBusinessUnitAddresses(): array
    {
        $companyBusinessUnit = $this->findCompanyBusinessUnit();
        if ($companyBusinessUnit === null) {
            return [];
        }

        $companyBusinessUnitAddressesArray = [];
        foreach ($companyBusinessUnit->getAddressCollection()->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            $companyBusinessUnitAddressesKey = static::PREFIX_KEY_COMPANY_BUSINESS_UNIT_ADDRESS . $companyUnitAddressTransfer->getKey();
            $companyBusinessUnitAddressesArray[$companyBusinessUnitAddressesKey] = $companyUnitAddressTransfer->toArray();
        }

        return $companyBusinessUnitAddressesArray;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null
     */
    protected function findCompanyBusinessUnit(): ?CompanyBusinessUnitTransfer
    {
        $customerTransfer = $this->getCustomer();

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if ($companyUserTransfer !== null) {
            return $companyUserTransfer->getCompanyBusinessUnit();
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomer(): CustomerTransfer
    {
        return $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();
    }
}
