<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;
use Spryker\Client\CompanyUserInvitation\CompanyUserInvitationClientInterface;

class CompanyUserInvitationPageToCompanyUserInvitationClientBridge implements CompanyUserInvitationPageToCompanyUserInvitationClientBridgeInterface
{
    /**
     * @var \Spryker\Client\CompanyUserInvitation\CompanyUserInvitationClientInterface
     */
    private $companyUserInvitationClient;

    /**
     * @param \Spryker\Client\CompanyUserInvitation\CompanyUserInvitationClientInterface $companyUserInvitationClient
     */
    public function __construct(CompanyUserInvitationClientInterface $companyUserInvitationClient)
    {
        $this->companyUserInvitationClient = $companyUserInvitationClient;
    }

    /**
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    public function importInvitations(string $filePath): CompanyUserInvitationImportResultTransfer
    {
        return $this->companyUserInvitationClient->importInvitations($filePath);
    }
}
