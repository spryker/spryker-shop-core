<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class MultiCartPageRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
{
    public const ROUTE_MULTI_CART_INDEX = 'multi-cart';
    public const ROUTE_MULTI_CART_CREATE = 'multi-cart/create';
    public const ROUTE_MULTI_CART_UPDATE = 'multi-cart/update';
    public const ROUTE_MULTI_CART_DELETE = 'multi-cart/delete';
    public const ROUTE_MULTI_CART_CONFIRM_DELETE = 'multi-cart/confirm-delete';
    public const ROUTE_MULTI_CART_SET_DEFAULT = 'multi-cart/set-default';
    public const ROUTE_MULTI_CART_CLEAR = 'multi-cart/clear';
    public const ROUTE_MULTI_CART_DUPLICATE = 'multi-cart/duplicate';

    public const PARAM_ID_QUOTE = 'idQuote';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $routeCollection = $this->addMultiCartCreateRoute($routeCollection);
        $routeCollection = $this->addMultiCartUpdateRoute($routeCollection);
        $routeCollection = $this->addMultiCartDeleteRoute($routeCollection);
        $routeCollection = $this->addMultiCartConfirmDeleteRoute($routeCollection);
        $routeCollection = $this->addMultiCartClearRoute($routeCollection);
        $routeCollection = $this->addMultiCartDuplicateRoute($routeCollection);
        $routeCollection = $this->addMultiCartSetDefaultRoute($routeCollection);
        $routeCollection = $this->addMultiCartIndexRoute($routeCollection);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addMultiCartCreateRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/create', 'MultiCartPage', 'MultiCart', 'createAction');
        $routeCollection->add(static::ROUTE_MULTI_CART_CREATE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addMultiCartUpdateRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/update/{idQuote}', 'MultiCartPage', 'MultiCart', 'updateAction');
        $route = $route->assert(self::PARAM_ID_QUOTE, '\d+');
        $routeCollection->add(static::ROUTE_MULTI_CART_UPDATE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addMultiCartDeleteRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/delete/{idQuote}', 'MultiCartPage', 'MultiCart', 'deleteAction');
        $route = $route->assert(self::PARAM_ID_QUOTE, '\d+');
        $routeCollection->add(static::ROUTE_MULTI_CART_DELETE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addMultiCartConfirmDeleteRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/confirm-delete/{idQuote}', 'MultiCartPage', 'MultiCart', 'confirmDeleteAction');
        $route = $route->assert(static::PARAM_ID_QUOTE, '\d+');
        $routeCollection->add(static::ROUTE_MULTI_CART_CONFIRM_DELETE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addMultiCartClearRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/clear/{idQuote}', 'MultiCartPage', 'MultiCart', 'clearAction');
        $route = $route->assert(self::PARAM_ID_QUOTE, '\d+');
        $routeCollection->add(static::ROUTE_MULTI_CART_CLEAR, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addMultiCartDuplicateRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/duplicate/{idQuote}', 'MultiCartPage', 'MultiCart', 'duplicateAction');
        $route = $route->assert(self::PARAM_ID_QUOTE, '\d+');
        $routeCollection->add(static::ROUTE_MULTI_CART_DUPLICATE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addMultiCartSetDefaultRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/set-default/{idQuote}', 'MultiCartPage', 'MultiCart', 'setDefaultAction');
        $route = $route->assert(self::PARAM_ID_QUOTE, '\d+');
        $routeCollection->add(static::ROUTE_MULTI_CART_SET_DEFAULT, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addMultiCartIndexRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/multi-cart', 'MultiCartPage', 'MultiCart', 'indexAction');
        $routeCollection->add(static::ROUTE_MULTI_CART_INDEX, $route);
        return $routeCollection;
    }
}
