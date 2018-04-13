<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserTransfer;

class CompanyUserInvitationPageToCompanyUserClientBridge implements CompanyUserInvitationPageToCompanyUserClientInterface
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
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function updateCompanyUser(CompanyUserTransfer $companyUserTransfer)
    {
        return $this->companyUserClient->updateCompanyUser($companyUserTransfer);
    }
}
