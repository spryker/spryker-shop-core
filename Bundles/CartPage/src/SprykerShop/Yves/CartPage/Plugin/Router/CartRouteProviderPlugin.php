<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Symfony\Component\HttpFoundation\Request;

class CartRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
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
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
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
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCartRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/cart', 'CartPage', 'Cart', 'indexAction');
        $route = $route->convert('selectedAttributes', [$this, 'getSelectedAttributesFromRequest']);
        $routeCollection->add(static::ROUTE_CART, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCartAddItemsRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/cart/add-items', 'CartPage', 'Cart', 'addItemsAction');
        $routeCollection->add(static::ROUTE_CART_ADD_ITEMS, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCartAddRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/cart/add/{sku}', 'CartPage', 'Cart', 'addAction');
        $route = $route->assert('sku', self::SKU_PATTERN);
        $route = $route->convert('quantity', [$this, 'getQuantityFromRequest']);
        $route = $route->convert('optionValueIds', [$this, 'getProductOptionsFromRequest']);
        $routeCollection->add(static::ROUTE_CART_ADD, $route);
        return $routeCollection;
    }

    /**
     * @uses CartControllerProvider::getQuantityFromRequest()
     * @uses CartController::quickAddAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCartQuickAddRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/cart/quick-add/{sku}', 'CartPage', 'Cart', 'quickAddAction');
        $route = $route->assert('sku', static::SKU_PATTERN);
        $route = $route->convert('quantity', [$this, 'getQuantityFromRequest']);
        $routeCollection->add(static::ROUTE_CART_QUICK_ADD, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCartRemoveRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/cart/remove/{sku}/', 'CartPage', 'Cart', 'removeAction');
        $route = $route->assert('sku', self::SKU_PATTERN);
        $routeCollection->add(static::ROUTE_CART_REMOVE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCartChangeQuantityRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/cart/change/{sku}', 'CartPage', 'Cart', 'changeAction');
        $route = $route->assert('sku', self::SKU_PATTERN);
        $route = $route->convert('quantity', [$this, 'getQuantityFromRequest']);
        $route = $route->convert('groupKey', [$this, 'getGroupKeyFromRequest']);
        $route = $route->method('POST');
        $routeCollection->add(static::ROUTE_CART_CHANGE_QUANTITY, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCartUpdateRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $controller = $this->createController('/{cart}/update/{sku}', self::ROUTE_CART_UPDATE, 'CartPage', 'Cart', 'update')
            ->assert('cart', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('cart', 'cart')
            ->assert('sku', self::SKU_PATTERN)
            ->convert('quantity', [$this, 'getQuantityFromRequest']);
        $controller->convert('groupKey', [$this, 'getGroupKeyFromRequest'])
            ->convert('selectedAttributes', [$this, 'getSelectedAttributesFromRequest'])
            ->convert('preselectedAttributes', [$this, 'getPreSelectedAttributesFromRequest'])
            ->convert('optionValueIds', [$this, 'getProductOptionsFromRequest'])
            ->method('POST');
        return $routeCollection;
    }

    /**
     * @param mixed $unusedParameter
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    public function getQuantityFromRequest($unusedParameter, Request $request)
    {
        if ($request->isMethod('POST')) {
            return $request->request->getInt('quantity', 1);
        }

        return $request->query->getInt('quantity', 1);
    }

    /**
     * @param mixed $unusedParameter
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    public function getSelectedAttributesFromRequest($unusedParameter, Request $request)
    {
        if ($request->isMethod('POST')) {
            return $request->request->get('selectedAttributes', []);
        }

        return $request->query->get('selectedAttributes', []);
    }

    /**
     * @param mixed $unusedParameter
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    public function getPreSelectedAttributesFromRequest($unusedParameter, Request $request)
    {
        if ($request->isMethod('POST')) {
            return $request->request->get('preselectedAttributes', []);
        }

        return $request->query->get('preselectedAttributes', []);
    }

    /**
     * @param mixed $unusedParameter
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    public function getProductOptionsFromRequest($unusedParameter, Request $request)
    {
        if ($request->isMethod('POST')) {
            return $request->request->get('product-option', []);
        }

        return $request->query->get('product-option', []);
    }

    /**
     * @param mixed $unusedParameter
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    public function getGroupKeyFromRequest($unusedParameter, Request $request)
    {
        if ($request->isMethod('POST')) {
            return $request->request->get('groupKey');
        }

        return $request->query->get('groupKey');
    }
}
