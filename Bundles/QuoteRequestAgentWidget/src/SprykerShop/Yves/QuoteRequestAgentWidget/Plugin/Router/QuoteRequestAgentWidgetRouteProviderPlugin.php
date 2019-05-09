<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class QuoteRequestAgentWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_QUOTE_REQUEST_AGENT_SAVE_CART = 'agent/quote-request/cart/save';
    protected const ROUTE_QUOTE_REQUEST_AGENT_CLEAR_CART = 'agent/quote-request/cart/clear';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addQuoteRequestSaveCartRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestClearCartRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentWidget\Controller\QuoteRequestAgentCartController::saveAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestSaveCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/cart/save', 'QuoteRequestAgentWidget', 'QuoteRequestAgentCart', 'saveAction');
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_AGENT_SAVE_CART, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentWidget\Controller\QuoteRequestAgentCartController::clearAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestClearCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/cart/clear', 'QuoteRequestAgentWidget', 'QuoteRequestAgentCart', 'clearAction');
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_AGENT_CLEAR_CART, $route);

        return $routeCollection;
    }
}
