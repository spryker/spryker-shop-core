<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Impersonator;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CompanyUserImpersonatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return array
     */
    public function getImpersonationCompanyUserExitParams(QuoteRequestTransfer $quoteRequestTransfer, ?CompanyUserTransfer $companyUserTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return array
     */
    public function getImpersonationCompanyUserEmailParams(QuoteRequestTransfer $quoteRequestTransfer, ?CompanyUserTransfer $companyUserTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function convertQuoteRequestToQuote(QuoteRequestTransfer $quoteRequestTransfer, QuoteTransfer $quoteTransfer): void;
}
