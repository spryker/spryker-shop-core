<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageFactory getFactory()
 */
class QuoteRequestAgentCheckoutAddressController extends QuoteRequestAgentAbstractController
{
    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::CHECKOUT_ADDRESS
     */
    protected const ROUTE_CHECKOUT_ADDRESS = 'checkout-address';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function executeIndexAction(Request $request, string $quoteRequestReference)
    {
        $quoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestReference);

        return $this->getFactory()
            ->createCompanyUserImpersonator()
            ->impersonateCompanyUser($quoteRequestTransfer, static::ROUTE_CHECKOUT_ADDRESS);
    }
}
