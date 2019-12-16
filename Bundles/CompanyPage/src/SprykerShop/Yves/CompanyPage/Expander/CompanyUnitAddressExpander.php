<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Expander;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CompanyPage\Mapper\CompanyUnitMapperInterface;

class CompanyUnitAddressExpander implements CompanyUnitAddressExpanderInterface
{
    /**
     * @var \SprykerShop\Yves\CompanyPage\Mapper\CompanyUnitMapperInterface
     */
    protected $companyUnitMapper;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Mapper\CompanyUnitMapperInterface $companyUnitMapper
     */
    public function __construct(CompanyUnitMapperInterface $companyUnitMapper)
    {
        $this->companyUnitMapper = $companyUnitMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function expandWithCompanyUnitAddress(AddressTransfer $addressTransfer, ?CustomerTransfer $customerTransfer): AddressTransfer
    {
        if ($addressTransfer->getIdCompanyUnitAddress() === null) {
            return $addressTransfer;
        }

        $addressTransfer->setIsAddressSavingSkipped(true);
        $addressTransfer = $this->convertIdCompanyUnitAddressToInt($addressTransfer);

        if ($customerTransfer === null) {
            return $addressTransfer;
        }

        foreach ($this->getCompanyUnitAddresses($customerTransfer) as $companyUnitAddressTransfer) {
            if ($addressTransfer->getIdCompanyUnitAddress() !== $companyUnitAddressTransfer->getIdCompanyUnitAddress()) {
                continue;
            }

            return $this->prepareCompanyUnitAddress($companyUnitAddressTransfer, $customerTransfer);
        }

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function convertIdCompanyUnitAddressToInt(AddressTransfer $addressTransfer): AddressTransfer
    {
        $idCompanyUnitAddress = (int)$addressTransfer->getIdCompanyUnitAddress();
        $addressTransfer->setIdCompanyUnitAddress($idCompanyUnitAddress);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function prepareCompanyUnitAddress(CompanyUnitAddressTransfer $companyUnitAddressTransfer, CustomerTransfer $customerTransfer): AddressTransfer
    {
        $addressTransfer = $this->companyUnitMapper
            ->mapCompanyUnitAddressTransferToAddressTransfer($companyUnitAddressTransfer, new AddressTransfer());

        $addressTransfer = $this->companyUnitMapper->mapCustomerDataToAddressTransfer($addressTransfer, $customerTransfer);
        $addressTransfer->setCompany($this->getCompanyName($customerTransfer));
        $addressTransfer->setIsAddressSavingSkipped(true);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CompanyUnitAddressTransfer[]
     */
    protected function getCompanyUnitAddresses(CustomerTransfer $customerTransfer): ArrayObject
    {
        $companyBusinessUnitTransfer = $this->findCompanyBusinessUnit($customerTransfer);
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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null
     */
    protected function findCompanyBusinessUnit(CustomerTransfer $customerTransfer): ?CompanyBusinessUnitTransfer
    {
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if ($companyUserTransfer === null) {
            return null;
        }

        return $companyUserTransfer->getCompanyBusinessUnit();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string|null
     */
    protected function getCompanyName(CustomerTransfer $customerTransfer): ?string
    {
        $companyBusinessUnitTransfer = $this->findCompanyBusinessUnit($customerTransfer);
        if ($companyBusinessUnitTransfer === null) {
            return null;
        }

        $companyTransfer = $companyBusinessUnitTransfer->getCompany();
        if ($companyTransfer === null) {
            return null;
        }

        return $companyTransfer->getName();
    }
}
