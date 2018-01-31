<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Dependency\Client;

use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;

interface CompanyPageToCompanyRoleClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function createCompanyRole(CompanyRoleTransfer $companyRoleUserTransfer): CompanyRoleResponseTransfer;
}