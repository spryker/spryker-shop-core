<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Generator;

use SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToMessengerClientInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;

class RedirectResponseGenerator implements RedirectResponseGeneratorInterface
{
    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::CHECKOUT_SHIPMENT
     */
    protected const ROUTE_REDIRECT_CHECKOUT_SHIPMENT = 'checkout-shipment';
    protected const GLOSSARY_KEY_SHIPMENT_SUCCESS_SAVE = 'global.shipment.success.save';

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @var \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    protected $router;

    /**
     * @param \Symfony\Cmf\Component\Routing\ChainRouterInterface $router
     * @param \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToMessengerClientInterface $messengerClient
     */
    public function __construct(
        ChainRouterInterface $router,
        QuoteRequestAgentWidgetToMessengerClientInterface $messengerClient
    ) {
        $this->router = $router;
        $this->messengerClient = $messengerClient;
    }

    /**
     * @return string
     */
    public function generateCheckoutShipmentRedirectUrl(): string
    {
        $checkoutShipmentUrl = $this->router->generate(static::ROUTE_REDIRECT_CHECKOUT_SHIPMENT);
        $this->messengerClient->addSuccessMessage(static::GLOSSARY_KEY_SHIPMENT_SUCCESS_SAVE);

        return $checkoutShipmentUrl;
    }
}
