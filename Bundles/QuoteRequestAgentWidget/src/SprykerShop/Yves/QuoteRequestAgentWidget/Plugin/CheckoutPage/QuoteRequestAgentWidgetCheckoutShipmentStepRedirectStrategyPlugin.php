<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepRedirectStrategyPluginInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentWidget\QuoteRequestAgentWidgetFactory getFactory()
 */
class QuoteRequestAgentWidgetCheckoutShipmentStepRedirectStrategyPlugin extends AbstractPlugin implements CheckoutShipmentStepRedirectStrategyPluginInterface
{
    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::CHECKOUT_SHIPMENT
     */
    protected const ROUTE_REDIRECT_CHECKOUT_SHIPMENT = 'checkout-shipment';
    protected const GLOSSARY_KEY_SHIPMENT_SUCCESS_SAVE = 'global.shipment.success.save';

    /**
     * {@inheritDoc}
     * - Checks if quote contains request references applicable for Agent.
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
     *  - Returns a redirect response.
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
        $checkoutShipmentUrl = $this->getFactory()->getRouterService()->generate(static::ROUTE_REDIRECT_CHECKOUT_SHIPMENT);
        $this->getFactory()->getMessengerClient()->addSuccessMessage(static::GLOSSARY_KEY_SHIPMENT_SUCCESS_SAVE);

        return $this->getFactory()->createRedirectResponse($checkoutShipmentUrl);
    }
}
