<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Plugin\Shipment;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuoteRequestAgentPageExtension\Dependency\Plugin\CheckoutShipmentPostExecuteStrategyPluginInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class QuoteRequestAgentWidgetCheckoutShipmentPostExecuteStrategyPlugin extends AbstractPlugin implements CheckoutShipmentPostExecuteStrategyPluginInterface
{
    protected const ROUTE_REDIRECT_CHECKOUT_SHIPMENT = 'checkout-shipment';
    protected const ROUTE_REDIRECT_CODE = 302;

    /**
     * {@inheritDoc}
     * -
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool
    {
        if ($quoteTransfer->getQuoteRequestReference() && $quoteTransfer->getQuoteRequestVersionReference()) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     *  -
     *
     * @api
     *
     * @param \Symfony\Cmf\Component\Routing\ChainRouterInterface $router
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function execute(ChainRouterInterface $router): RedirectResponse
    {
        return new RedirectResponse($router->generate(self::ROUTE_REDIRECT_CHECKOUT_SHIPMENT, []), self::ROUTE_REDIRECT_CODE);
    }
}
