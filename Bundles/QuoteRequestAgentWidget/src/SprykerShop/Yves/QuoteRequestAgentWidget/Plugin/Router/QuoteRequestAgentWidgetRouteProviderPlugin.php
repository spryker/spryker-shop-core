<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class QuoteRequestAgentWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentWidget\Plugin\Router\QuoteRequestAgentWidgetRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_SAVE_CART} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_SAVE_CART = 'agent/quote-request/cart/save';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_SAVE_CART = 'agent/quote-request/cart/save';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentWidget\Plugin\Router\QuoteRequestAgentWidgetRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CLEAR_CART} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_CLEAR_CART = 'agent/quote-request/cart/clear';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_CLEAR_CART = 'agent/quote-request/cart/clear';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
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
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestSaveCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/cart/save', 'QuoteRequestAgentWidget', 'QuoteRequestAgentCart', 'saveAction');
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_SAVE_CART, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentWidget\Controller\QuoteRequestAgentCartController::clearAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestClearCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/cart/clear', 'QuoteRequestAgentWidget', 'QuoteRequestAgentCart', 'clearAction');
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_CLEAR_CART, $route);

        return $routeCollection;
    }
}
