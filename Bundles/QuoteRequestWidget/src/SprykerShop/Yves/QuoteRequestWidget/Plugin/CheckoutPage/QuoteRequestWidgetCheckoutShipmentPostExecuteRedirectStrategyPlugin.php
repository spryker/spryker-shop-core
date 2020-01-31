<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentPostExecuteRedirectStrategyPluginInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetFactory getFactory()
 */
class QuoteRequestWidgetCheckoutShipmentPostExecuteRedirectStrategyPlugin extends AbstractPlugin implements CheckoutShipmentPostExecuteRedirectStrategyPluginInterface
{
    /**
     * @uses \SprykerShop\Yves\QuoteRequestWidget\Plugin\Router\QuoteRequestWidgetRouteProviderPlugin::ROUTE_QUOTE_REQUEST_SAVE_CART
     */
    protected const ROUTE_QUOTE_REQUEST_SAVE_CART = 'quote-request/cart/save';

    /**
     * {@inheritDoc}
     * - Checks if this plugin is applicable for provided quote.
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
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function execute(RedirectResponse $redirectResponse): RedirectResponse
    {
        $checkoutShipmentUrl = $this->getFactory()
            ->getRouterService()
            ->generate(static::ROUTE_QUOTE_REQUEST_SAVE_CART);

        return $this->getFactory()->createRedirectResponse($checkoutShipmentUrl);
    }
}
