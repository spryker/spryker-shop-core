<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Dependency\Client;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;

class CompanyPageToCompanyBusinessUnitClientBridge implements CompanyPageToCompanyBusinessUnitClientInterface
{
    /**
     * @var \Spryker\Client\CompanyBusinessUnit\CompanyBusinessUnitClientInterface
     */
    protected $companyBusinessUnitClient;

    /**
     * @param \Spryker\Client\CompanyBusinessUnit\CompanyBusinessUnitClientInterface $companyBusinessUnitClient
     */
    public function __construct($companyBusinessUnitClient)
    {
        $this->companyBusinessUnitClient = $companyBusinessUnitClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function createCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        return $this->companyBusinessUnitClient->createCompanyBusinessUnit($companyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $businessUnitCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getCompanyBusinessUnitCollection(
        CompanyBusinessUnitCollectionTransfer $businessUnitCollectionTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        return $this->companyBusinessUnitClient->getCompanyBusinessUnitCollection($businessUnitCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function getCompanyBusinessUnitById(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        return $this->companyBusinessUnitClient->getCompanyBusinessUnitById($companyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function updateCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        return $this->companyBusinessUnitClient->updateCompanyBusinessUnit($companyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return bool
     */
    public function deleteCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): bool {
        return $this->companyBusinessUnitClient->deleteCompanyBusinessUnit($companyBusinessUnitTransfer);
    }
}
