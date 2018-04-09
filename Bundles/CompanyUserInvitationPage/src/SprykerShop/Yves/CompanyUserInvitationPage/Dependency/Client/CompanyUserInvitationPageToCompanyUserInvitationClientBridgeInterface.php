<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;

interface CompanyUserInvitationPageToCompanyUserInvitationClientBridgeInterface
{
    /**
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    public function importInvitations(string $filePath): CompanyUserInvitationImportResultTransfer;
}
