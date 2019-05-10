<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class CartPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_CART = 'cart';
    public const ROUTE_CART_ADD = 'cart/add';
    public const ROUTE_CART_QUICK_ADD = 'cart/quick-add';
    public const ROUTE_CART_REMOVE = 'cart/remove';
    public const ROUTE_CART_CHANGE = 'cart/change';
    public const ROUTE_CART_UPDATE = 'cart/update';
    public const ROUTE_CART_CHANGE_QUANTITY = 'cart/change/quantity';
    public const ROUTE_CART_ADD_ITEMS = 'cart/add-items';
    public const SKU_PATTERN = '[a-zA-Z0-9-_\.]+';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addCartRoute($routeCollection);
        $routeCollection = $this->addCartAddItemsRoute($routeCollection);
        $routeCollection = $this->addCartAddRoute($routeCollection);
        $routeCollection = $this->addCartRemoveRoute($routeCollection);
        $routeCollection = $this->addCartChangeQuantityRoute($routeCollection);
        $routeCollection = $this->addCartUpdateRoute($routeCollection);
        $routeCollection = $this->addCartQuickAddRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart', 'CartPage', 'Cart');

        $routeCollection->add(self::ROUTE_CART, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addCartAddItemsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildPostRoute('/cart/add-items', 'CartPage', 'Cart', 'addItems');

        $routeCollection->add(self::ROUTE_CART_ADD_ITEMS, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addCartAddRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/add/{sku}', 'CartPage', 'Cart', 'add')
            ->setRequirement('sku', self::SKU_PATTERN);

        $routeCollection->add(self::ROUTE_CART_ADD, $route);

        return $routeCollection;
    }

    /**
     * @uses CartControllerProvider::getQuantityFromRequest()
     * @uses CartController::quickAddAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addCartQuickAddRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/quick-add/{sku}', 'CartPage', 'Cart', 'quickAdd')
            ->setRequirement('sku', static::SKU_PATTERN);

        $routeCollection->add(self::ROUTE_CART_QUICK_ADD, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addCartRemoveRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/remove/{sku}/{groupKey}', 'CartPage', 'Cart', 'remove')
            ->setRequirement('sku', self::SKU_PATTERN)
            ->setDefault('groupKey', '');

        $routeCollection->add(self::ROUTE_CART_REMOVE, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addCartChangeQuantityRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/change/{sku}', 'CartPage', 'Cart', 'change')
            ->setRequirement('sku', self::SKU_PATTERN)
            ->setMethods('POST');

        $routeCollection->add(self::ROUTE_CART_CHANGE_QUANTITY, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addCartUpdateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildPostRoute('/cart/update/{sku}', 'CartPage', 'Cart', 'update')
            ->setRequirement('sku', self::SKU_PATTERN);

        $routeCollection->add(self::ROUTE_CART_UPDATE, $route);

        return $routeCollection;
    }
}
