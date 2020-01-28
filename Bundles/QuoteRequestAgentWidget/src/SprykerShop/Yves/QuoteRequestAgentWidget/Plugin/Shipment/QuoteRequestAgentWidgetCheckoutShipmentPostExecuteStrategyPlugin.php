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
    protected const GLOSSARY_KEY_SHIPMENT_SUCCESS_SAVE = 'global.shipment.success.save';

    /**
     * {@inheritDoc}
     * - Checks if this is an Agent.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getQuoteRequestReference() && $quoteTransfer->getQuoteRequestVersionReference();
    }

    /**
     * {@inheritDoc}
     *  - Returns a redirect response with additional header.
     *
     * @api
     *
     * @param \Symfony\Cmf\Component\Routing\ChainRouterInterface $router
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function execute(ChainRouterInterface $router): RedirectResponse
    {
        $redirectResponse = new RedirectResponse($router->generate(static::ROUTE_REDIRECT_CHECKOUT_SHIPMENT));
        $redirectResponse->headers->set(static::GLOSSARY_KEY_SHIPMENT_SUCCESS_SAVE, static::GLOSSARY_KEY_SHIPMENT_SUCCESS_SAVE);

        return $redirectResponse;
    }
}
