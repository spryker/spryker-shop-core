<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Dependency\Client;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

class CompanyPageToCompanyUnitAddressClientBridge implements CompanyPageToCompanyUnitAddressClientInterface
{
    /**
     * @var \Spryker\Client\CompanyUnitAddress\CompanyUnitAddressClientInterface
     */
    protected $companyUnitAddressClient;

    /**
     * @param \Spryker\Client\CompanyUnitAddress\CompanyUnitAddressClientInterface $companyUnitAddressClient
     */
    public function __construct($companyUnitAddressClient)
    {
        $this->companyUnitAddressClient = $companyUnitAddressClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getCompanyUnitAddressById(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer {
        return $this->companyUnitAddressClient->getCompanyUnitAddressById($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function createCompanyUnitAddress(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressResponseTransfer {
        return $this->companyUnitAddressClient->createCompanyUnitAddress($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function getCompanyUnitAddressCollection(
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUnitAddressCollectionTransfer {
        return $this->companyUnitAddressClient->getCompanyUnitAddressCollection($criteriaFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    public function deleteCompanyUnitAddress(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $this->companyUnitAddressClient->deleteCompanyUnitAddress($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return void
     */
    public function saveCompanyBusinessUnitAddresses(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): void {
        $this->companyUnitAddressClient->saveCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function updateCompanyUnitAddress(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        return $this->companyUnitAddressClient->updateCompanyUnitAddress($companyUnitAddressTransfer);
    }
}
