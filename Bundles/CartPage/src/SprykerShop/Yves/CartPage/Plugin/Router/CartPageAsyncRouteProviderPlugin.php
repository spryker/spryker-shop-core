<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

class CartPageAsyncRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_CART_ASYNC_REMOVE = 'cart/async/remove';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_ASYNC_CHANGE_QUANTITY = 'cart/async/change-quantity';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_ASYNC_QUICK_ADD = 'cart/async/quick-add';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_ASYNC_ADD = 'cart/async/add';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_ASYNC_UPDATE = 'cart/async/update';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_ASYNC_VIEW = 'cart/async/view';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_ASYNC_MINI_CART_VIEW = 'cart/async/mini-cart-view';

    /**
     * @var string
     */
    protected const SKU_PATTERN = '[a-zA-Z0-9-_\.]+';

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
        $routeCollection = $this->addCartAsyncRemoveRoute($routeCollection);
        $routeCollection = $this->addCartAsyncChangeQuantityRoute($routeCollection);
        $routeCollection = $this->addCartAsyncQuickAddRoute($routeCollection);
        $routeCollection = $this->addCartAsyncAddRoute($routeCollection);
        $routeCollection = $this->addCartAsyncUpdateRoute($routeCollection);
        $routeCollection = $this->addCartAsyncViewRoute($routeCollection);
        $routeCollection = $this->addCartAsyncMiniCartViewRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CartPage\Controller\CartAsyncController::removeAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCartAsyncRemoveRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/async/remove/{sku}/{groupKey}', 'CartPage', 'CartAsync', 'removeAction');
        $route = $route->setRequirement('sku', static::SKU_PATTERN);
        $route = $route->setDefault('groupKey', '');
        $route = $route->setMethods(Request::METHOD_POST);

        $routeCollection->add(static::ROUTE_NAME_CART_ASYNC_REMOVE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CartPage\Controller\CartAsyncController::changeQuantityAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCartAsyncChangeQuantityRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/async/change-quantity/{sku}', 'CartPage', 'CartAsync', 'changeQuantityAction');
        $route = $route->setRequirement('sku', static::SKU_PATTERN);
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_CART_ASYNC_CHANGE_QUANTITY, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CartPage\Controller\CartAsyncController::quickAddAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCartAsyncQuickAddRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/async/quick-add', 'CartPage', 'CartAsync', 'quickAddAction');
        $routeCollection->add(static::ROUTE_NAME_CART_ASYNC_QUICK_ADD, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CartPage\Controller\CartAsyncController::addAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCartAsyncAddRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/async/add/{sku}', 'CartPage', 'CartAsync', 'addAction');
        $route = $route->setRequirement('sku', static::SKU_PATTERN);
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_CART_ASYNC_ADD, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CartPage\Controller\CartAsyncController::updateAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCartAsyncUpdateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/async/update/{sku}', 'CartPage', 'CartAsync', 'updateAction');
        $route = $route->setRequirement('sku', static::SKU_PATTERN);
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_CART_ASYNC_UPDATE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CartPage\Controller\CartAsyncController::viewAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCartAsyncViewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/async/view', 'CartPage', 'CartAsync', 'viewAction');
        $routeCollection->add(static::ROUTE_NAME_CART_ASYNC_VIEW, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CartPage\Controller\CartAsyncController::viewAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCartAsyncMiniCartViewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/async/mini-cart-view', 'CartPage', 'CartAsync', 'miniCartViewAction');
        $routeCollection->add(static::ROUTE_NAME_CART_ASYNC_MINI_CART_VIEW, $route);

        return $routeCollection;
    }
}
