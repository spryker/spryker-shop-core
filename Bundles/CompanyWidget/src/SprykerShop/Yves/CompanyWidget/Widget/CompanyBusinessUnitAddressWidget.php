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
    /**
     * @param string $formType
     */
    public function __construct(string $formType)
    {
        $this->addParameter('formType', $formType)
            ->addParameter('isApplicable', $this->isApplicable())
            ->addParameter('addresses', $this->findAddressesJson($formType));
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

        if (empty($this->getCompanyBusinessUnitAddresses()->getCompanyUnitAddresses())) {
            return false;
        }

        return true;
    }

    /**
     * @param string $formType
     *
     * @return string|null
     */
    protected function findAddressesJson(string $formType): ?string
    {
        $dataProvider = $this->getFactory()
            ->createCompanyBusinessUnitAddressWidgetDataProvider();

        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $customerAddressesArray = $dataProvider->getCustomerAddresses($customerTransfer->getAddresses(), $formType);

        $companyBusinessUnitAddressesArray = $dataProvider->getCompanyBusinessUnitAddresses(
            $this->getCompanyBusinessUnitAddresses(),
            $formType,
            $customerTransfer
        );

        return $this->encodeAddressesToJson(array_merge($customerAddressesArray, $companyBusinessUnitAddressesArray));
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    protected function getCompanyBusinessUnitAddresses(): CompanyUnitAddressCollectionTransfer
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if ($companyUserTransfer === null) {
            return new CompanyUnitAddressCollectionTransfer();
        }

        return $companyUserTransfer->getCompanyBusinessUnit()
            ->getAddressCollection();
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
