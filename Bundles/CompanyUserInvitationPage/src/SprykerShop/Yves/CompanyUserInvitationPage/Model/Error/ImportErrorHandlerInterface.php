<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Model\Error;

use Generated\Shared\Transfer\CompanyUserInvitationImportResponseTransfer;
use Iterator;

interface ImportErrorHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationImportResponseTransfer $companyUserInvitationImportReportTransfer
     *
     * @return mixed
     */
    public function storeCompanyUserInvitationImportErrors(
        CompanyUserInvitationImportResponseTransfer $companyUserInvitationImportReportTransfer
    );

    /**
     * @return \Iterator
     */
    public function retrieveCompanyUserInvitationImportErrors(): Iterator;
}
