<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

class CompanyPageToCompanyUserClientBridge implements CompanyPageToCompanyUserClientInterface
{
    /**
     * @var \Spryker\Client\CompanyUser\CompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @param \Spryker\Client\CompanyUser\CompanyUserClientInterface $companyUserClient
     */
    public function __construct($companyUserClient)
    {
        $this->companyUserClient = $companyUserClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function createCompanyUser(CompanyUserTransfer $companyUserUserTransfer): CompanyUserResponseTransfer
    {
        return $this->companyUserClient->createCompanyUser($companyUserUserTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function getCompanyUser(): ?CompanyUserTransfer
    {
        return $this->companyUserClient->getCompanyUser();
    }
}
