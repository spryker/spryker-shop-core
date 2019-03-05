<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class AgentQuoteRequestWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_AGENT_QUOTE_REQUEST_CART = 'agent/quote-request/cart';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addQuoteRequestItemsRoute();
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestWidget\Controller\AgentQuoteRequestCartController::indexAction()
     *
     * @return $this
     */
    protected function addQuoteRequestItemsRoute()
    {
        $this->createController('/{agent}/quote-request/cart', static::ROUTE_AGENT_QUOTE_REQUEST_CART, 'AgentQuoteRequestWidget', 'AgentQuoteRequestCart', 'index')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }
}
