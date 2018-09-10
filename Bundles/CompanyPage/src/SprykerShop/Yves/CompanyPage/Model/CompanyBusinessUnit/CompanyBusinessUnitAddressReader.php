<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface;

class CompanyBusinessUnitAddressReader implements CompanyBusinessUnitAddressReaderInterface
{
    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface
     */
    protected $companyUnitAddressClient;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface $companyUnitAddressClient
     */
    public function __construct(CompanyPageToCompanyUnitAddressClientInterface $companyUnitAddressClient)
    {
        $this->companyUnitAddressClient = $companyUnitAddressClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer|null
     */
    public function getDefaultBillingAddress(CompanyUserTransfer $companyUserTransfer): ?CompanyUnitAddressTransfer
    {
        $companyUnitAddressTransfer = $this->createCompanyUnitAddressTransfer($companyUserTransfer);

        if (!$companyUnitAddressTransfer->getIdCompanyUnitAddress()) {
            return null;
        }

        return $this->companyUnitAddressClient->getCompanyUnitAddressById($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    protected function createCompanyUnitAddressTransfer(CompanyUserTransfer $companyUserTransfer): CompanyUnitAddressTransfer
    {
        $defaultBillingAddressId = $companyUserTransfer->getCompanyBusinessUnit()->getDefaultBillingAddress();

        $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();

        if ($defaultBillingAddressId === null) {
            return $companyUnitAddressTransfer;
        }

        return $companyUnitAddressTransfer
            ->setIdCompanyUnitAddress($defaultBillingAddressId);
    }
}
