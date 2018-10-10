<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Widget;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
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
        $companyUnitAddressCollectionTransfer = $this->findCompanyBusinessUnitAddresses();

        $this->addParameter('formType', $formType)
            ->addParameter('isApplicable', $this->isApplicable($companyUnitAddressCollectionTransfer))
            ->addParameter('customerAddresses', $this->encodeAddressesToJson($this->getCustomerAddresses()))
            ->addParameter('businessUnitAddresses', $this->encodeAddressesToJson(
                $this->mapCompanyBusinessUnitAddressesToAssociativeArray($companyUnitAddressCollectionTransfer)
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
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     *
     * @return bool
     */
    public function isApplicable(CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer): bool
    {
        if ($this->getCustomer()->getCompanyUserTransfer() === null) {
            return false;
        }

        if (empty($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses())) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     *
     * @return array Indexes are company business unit prefix + company business unit address key
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
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    protected function findCompanyBusinessUnitAddresses(): CompanyUnitAddressCollectionTransfer
    {
        $companyBusinessUnit = $this->findCompanyBusinessUnit();
        if ($companyBusinessUnit === null) {
            return new CompanyUnitAddressCollectionTransfer();
        }

        $companyUnitAddressCriteriaFilterTransfer = (new CompanyUnitAddressCriteriaFilterTransfer())
            ->setIdCompanyBusinessUnit($companyBusinessUnit->getIdCompanyBusinessUnit())
            ->setIdCompany($companyBusinessUnit->getFkCompany());

        return $this->getFactory()
            ->getCompanyUnitAddressClient()
            ->getCompanyUnitAddressCollection($companyUnitAddressCriteriaFilterTransfer);
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
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomer(): CustomerTransfer
    {
        return $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();
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
