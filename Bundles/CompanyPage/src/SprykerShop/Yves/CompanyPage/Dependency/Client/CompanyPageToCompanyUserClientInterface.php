<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyPageToCompanyUserClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function createCompanyUser(CompanyUserTransfer $companyUserUserTransfer): CompanyUserResponseTransfer;
}
