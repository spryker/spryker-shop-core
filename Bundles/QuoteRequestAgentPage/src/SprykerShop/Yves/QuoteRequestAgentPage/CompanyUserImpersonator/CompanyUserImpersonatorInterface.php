<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\CompanyUserImpersonator;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;

interface CompanyUserImpersonatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $routeToRedirect
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectImpersonatedUserWithPreparedQuoteAndMessage(QuoteRequestTransfer $quoteRequestTransfer, QuoteTransfer $quoteTransfer, string $routeToRedirect): RedirectResponse;
}
