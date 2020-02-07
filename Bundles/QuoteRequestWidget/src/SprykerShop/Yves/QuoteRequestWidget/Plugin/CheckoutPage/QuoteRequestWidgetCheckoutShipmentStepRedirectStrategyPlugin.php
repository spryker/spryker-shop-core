<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepRedirectStrategyPluginInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetFactory getFactory()
 */
class QuoteRequestWidgetCheckoutShipmentStepRedirectStrategyPlugin extends AbstractPlugin implements CheckoutShipmentStepRedirectStrategyPluginInterface
{
    /**
     * @uses \SprykerShop\Yves\QuoteRequestWidget\Plugin\Router\QuoteRequestWidgetRouteProviderPlugin::ROUTE_QUOTE_REQUEST_SAVE_CART
     */
    protected const ROUTE_QUOTE_REQUEST_SAVE_CART = 'quote-request/cart/save';

    /**
     * {@inheritDoc}
     * - Returns true if quote request reference is set, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool
    {
        return (bool)$quoteTransfer->getQuoteRequestReference();
    }

    /**
     * {@inheritDoc}
     * - Returns a redirect response to quote-request/cart/save.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\RedirectResponse $redirectResponse
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function execute(RedirectResponse $redirectResponse, QuoteTransfer $quoteTransfer): RedirectResponse
    {
        $checkoutShipmentUrl = $this->getFactory()
            ->getRouterService()
            ->generate(static::ROUTE_QUOTE_REQUEST_SAVE_CART);

        return $this->getFactory()->createRedirectResponse($checkoutShipmentUrl);
    }
}
