<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Impersonator;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;

interface CompanyUserImpersonatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param string $routeToRedirect
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function impersonateCompanyUser(QuoteRequestTransfer $quoteRequestTransfer, string $routeToRedirect): RedirectResponse;
}
