<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\QuoteRequestAgentWidget\Plugin\Router\QuoteRequestAgentWidgetRouteProviderPlugin` instead.
 */
class QuoteRequestAgentWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_QUOTE_REQUEST_AGENT_SAVE_CART = 'agent/quote-request/cart/save';
    protected const ROUTE_QUOTE_REQUEST_AGENT_CLEAR_CART = 'agent/quote-request/cart/clear';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addQuoteRequestSaveCartRoute()
            ->addQuoteRequestClearCartRoute();
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentWidget\Controller\QuoteRequestAgentCartController::saveAction()
     *
     * @return $this
     */
    protected function addQuoteRequestSaveCartRoute()
    {
        $this->createController('/{agent}/quote-request/cart/save', static::ROUTE_QUOTE_REQUEST_AGENT_SAVE_CART, 'QuoteRequestAgentWidget', 'QuoteRequestAgentCart', 'save')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentWidget\Controller\QuoteRequestAgentCartController::clearAction()
     *
     * @return $this
     */
    protected function addQuoteRequestClearCartRoute()
    {
        $this->createController('/{agent}/quote-request/cart/clear', static::ROUTE_QUOTE_REQUEST_AGENT_CLEAR_CART, 'QuoteRequestAgentWidget', 'QuoteRequestAgentCart', 'clear')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }
}
