<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class QuoteRequestWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_QUOTE_REQUEST_SAVE_CART = 'quote-request/cart/save';
    protected const ROUTE_QUOTE_REQUEST_CLEAR_CART = 'quote-request/cart/clear';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
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
     * @uses \SprykerShop\Yves\QuoteRequestWidget\Controller\QuoteRequestCartController::indexAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestSaveCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/cart/save', 'QuoteRequestWidget', 'QuoteRequestCart', 'saveAction');
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_SAVE_CART, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestWidget\Controller\QuoteRequestCartController::clearAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestClearCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/cart/clear', 'QuoteRequestWidget', 'QuoteRequestCart', 'clearAction');
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_CLEAR_CART, $route);

        return $routeCollection;
    }
}
