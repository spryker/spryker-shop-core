<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface MerchantRelationRequestPageToCompanyUserClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUser(): ?CompanyUserTransfer;
}
