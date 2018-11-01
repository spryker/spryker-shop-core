<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Address;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCustomerClientInterface;

class AddressProvider implements AddressProviderInterface
{
    protected const COMPANY_BUSINESS_UNIT_ADDRESS_KEY_PATTERN = 'company_business_unit_address_%s';
    protected const CUSTOMER_ADDRESS_KEY_PATTERN = 'customer_address_%s';

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
        $customerTransfer = $this->customerClient->getCustomer();

        if (!$customerTransfer) {
            return false;
        }

        return $customerTransfer->getCompanyUserTransfer() && ($this->getCompanyBusinessUnitAddressCollection($customerTransfer)->count() > 0);
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer[]
     */
    public function getIndexedCustomerAddressList(): array
    {
        $customerAddressTransferList = $this->getCustomerAddressList();
        foreach ($customerAddressTransferList as $addressTransfer) {
            $addressTransfer->setKey($this->getCustomerAddressKey($addressTransfer->getIdCustomerAddress()));
        }

        return $customerAddressTransferList->getArrayCopy();
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer[]
     */
    public function getIndexedCompanyBusinessUnitAddressList(): array
    {
        $customerTransfer = $this->customerClient->getCustomer();
        $companyBusinessUnitAddressTransferList = $this->getCompanyBusinessUnitAddressCollection($customerTransfer);
        $addressTransferList = [];
        foreach ($companyBusinessUnitAddressTransferList as $addressTransfer) {
            $addressTransferList[] = $this->mapCompanyBusinessUnitAddressToAddress($addressTransfer, $customerTransfer);
        }

        return $addressTransferList;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function mapCompanyBusinessUnitAddressToAddress(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        CustomerTransfer $customerTransfer
    ): AddressTransfer {
        $addressTransfer = (new AddressTransfer())
            ->fromArray($companyUnitAddressTransfer->modifiedToArray(), true);

        $addressTransfer = $this->setAddressCustomerAttributes($addressTransfer, $customerTransfer);
        $addressTransfer->setKey($this->getBusinessUnitAddressKey($companyUnitAddressTransfer->getIdCompanyUnitAddress()));

        $companyBusinessUnitTransfer = $this->findCompanyBusinessUnit($customerTransfer);
        if ($companyBusinessUnitTransfer) {
            $addressTransfer->setCompany(
                $companyBusinessUnitTransfer->getCompany()->getName()
            );
        }

        return $addressTransfer;
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\AddressTransfer[]
     */
    protected function getCustomerAddressList(): ArrayObject
    {
        $customerTransfer = $this->customerClient->getCustomer();

        return $customerTransfer->getAddresses()
            ->getAddresses();
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
     * @return \ArrayObject|\Generated\Shared\Transfer\CompanyUnitAddressTransfer[]
     */
    protected function getCompanyBusinessUnitAddressCollection(CustomerTransfer $customerTransfer): ArrayObject
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
     * @param int $idCustomerAddress
     *
     * @return string
     */
    protected function getCustomerAddressKey(int $idCustomerAddress): string
    {
        return sprintf(static::CUSTOMER_ADDRESS_KEY_PATTERN, $idCustomerAddress);
    }

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return string
     */
    protected function getBusinessUnitAddressKey(int $idCompanyUnitAddress): string
    {
        return sprintf(static::COMPANY_BUSINESS_UNIT_ADDRESS_KEY_PATTERN, $idCompanyUnitAddress);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function setAddressCustomerAttributes(
        AddressTransfer $addressTransfer,
        CustomerTransfer $customerTransfer
    ): AddressTransfer {
        $addressTransfer = $addressTransfer->setLastName($customerTransfer->getLastName())
            ->setFirstName($customerTransfer->getFirstName())
            ->setSalutation($customerTransfer->getSalutation());

        return $addressTransfer;
    }
}
