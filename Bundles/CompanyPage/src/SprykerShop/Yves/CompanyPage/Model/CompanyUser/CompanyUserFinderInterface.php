<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Model\CompanyUser;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserFinderInterface
{
    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCurrentCompanyUser(): ?CompanyUserTransfer;
}
