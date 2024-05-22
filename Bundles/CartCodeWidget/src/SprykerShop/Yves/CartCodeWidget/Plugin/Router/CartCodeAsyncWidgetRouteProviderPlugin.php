<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CartCodeAsyncWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_CART_CODE_ASYNC_ADD = 'cart-code/code-async/add';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_CODE_ASYNC_REMOVE = 'cart-code/code-async/remove';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_CODE_ASYNC_CLEAR = 'cart-code/code-async/clear';

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
        $routeCollection = $this->addAddCodeAsyncRoute($routeCollection);
        $routeCollection = $this->addRemoveCodeAsyncRoute($routeCollection);
        $routeCollection = $this->addClearCodeAsyncRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CartCodeWidget\Controller\CodeAsyncController::addAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAddCodeAsyncRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart-code/code-async/add', 'CartCodeWidget', 'CodeAsync', 'addAction');
        $routeCollection->add(static::ROUTE_NAME_CART_CODE_ASYNC_ADD, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CartCodeWidget\Controller\CodeAsyncController::removeAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRemoveCodeAsyncRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart-code/code-async/remove', 'CartCodeWidget', 'CodeAsync', 'removeAction');
        $routeCollection->add(static::ROUTE_NAME_CART_CODE_ASYNC_REMOVE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CartCodeWidget\Controller\CodeAsyncController::clearAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addClearCodeAsyncRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart-code/code-async/clear', 'CartCodeWidget', 'CodeAsync', 'clearAction');
        $routeCollection->add(static::ROUTE_NAME_CART_CODE_ASYNC_CLEAR, $route);

        return $routeCollection;
    }
}
