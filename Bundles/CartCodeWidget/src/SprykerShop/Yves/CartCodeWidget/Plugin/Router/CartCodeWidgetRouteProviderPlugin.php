<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CartCodeWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_CART_CODE_ADD = 'cart-code/code/add';
    protected const ROUTE_CART_CODE_REMOVE = 'cart-code/code/remove';
    protected const ROUTE_CART_CODE_CLEAR = 'cart-code/code/clear';

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
        $routeCollection = $this->addAddCodeRoute($routeCollection);
        $routeCollection = $this->addRemoveCodeRoute($routeCollection);
        $routeCollection = $this->addClearCodeRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAddCodeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart-code/code/add', 'CartCodeWidget', 'Code', 'addAction');
        $routeCollection->add(static::ROUTE_CART_CODE_ADD, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRemoveCodeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart-code/code/remove', 'CartCodeWidget', 'Code', 'removeAction');
        $routeCollection->add(static::ROUTE_CART_CODE_REMOVE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addClearCodeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart-code/code/clear', 'CartCodeWidget', 'Code', 'clearAction');
        $routeCollection->add(static::ROUTE_CART_CODE_CLEAR, $route);

        return $routeCollection;
    }
}
