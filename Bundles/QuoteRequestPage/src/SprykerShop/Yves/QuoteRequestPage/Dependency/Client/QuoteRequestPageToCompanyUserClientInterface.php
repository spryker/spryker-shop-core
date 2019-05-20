<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface QuoteRequestPageToCompanyUserClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUser(): ?CompanyUserTransfer;
}
