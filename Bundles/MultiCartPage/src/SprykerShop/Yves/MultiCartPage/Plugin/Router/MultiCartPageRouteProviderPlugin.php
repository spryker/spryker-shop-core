<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class MultiCartPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_MULTI_CART_INDEX = 'multi-cart';
    protected const ROUTE_MULTI_CART_CREATE = 'multi-cart/create';
    protected const ROUTE_MULTI_CART_UPDATE = 'multi-cart/update';
    protected const ROUTE_MULTI_CART_DELETE = 'multi-cart/delete';
    protected const ROUTE_MULTI_CART_CONFIRM_DELETE = 'multi-cart/confirm-delete';
    protected const ROUTE_MULTI_CART_SET_DEFAULT = 'multi-cart/set-default';
    protected const ROUTE_MULTI_CART_CLEAR = 'multi-cart/clear';
    protected const ROUTE_MULTI_CART_DUPLICATE = 'multi-cart/duplicate';

    protected const PARAM_ID_QUOTE = 'idQuote';

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
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMultiCartCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/create', 'MultiCartPage', 'MultiCart', 'createAction');
        $routeCollection->add(static::ROUTE_MULTI_CART_CREATE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMultiCartUpdateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/update/{idQuote}', 'MultiCartPage', 'MultiCart', 'updateAction');
        $route = $route->setRequirement(static::PARAM_ID_QUOTE, '\d+');
        $routeCollection->add(static::ROUTE_MULTI_CART_UPDATE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMultiCartDeleteRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/delete/{idQuote}', 'MultiCartPage', 'MultiCart', 'deleteAction');
        $route = $route->setRequirement(static::PARAM_ID_QUOTE, '\d+');
        $routeCollection->add(static::ROUTE_MULTI_CART_DELETE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMultiCartConfirmDeleteRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/confirm-delete/{idQuote}', 'MultiCartPage', 'MultiCart', 'confirmDeleteAction');
        $route = $route->setRequirement(static::PARAM_ID_QUOTE, '\d+');
        $routeCollection->add(static::ROUTE_MULTI_CART_CONFIRM_DELETE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMultiCartClearRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/clear/{idQuote}', 'MultiCartPage', 'MultiCart', 'clearAction');
        $route = $route->setRequirement(static::PARAM_ID_QUOTE, '\d+');
        $routeCollection->add(static::ROUTE_MULTI_CART_CLEAR, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMultiCartDuplicateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/duplicate/{idQuote}', 'MultiCartPage', 'MultiCart', 'duplicateAction');
        $route = $route->setRequirement(static::PARAM_ID_QUOTE, '\d+');
        $routeCollection->add(static::ROUTE_MULTI_CART_DUPLICATE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMultiCartSetDefaultRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/multi-cart/set-default/{idQuote}', 'MultiCartPage', 'MultiCart', 'setDefaultAction');
        $route = $route->setRequirement(static::PARAM_ID_QUOTE, '\d+');
        $routeCollection->add(static::ROUTE_MULTI_CART_SET_DEFAULT, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMultiCartIndexRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/multi-cart', 'MultiCartPage', 'MultiCart', 'indexAction');
        $routeCollection->add(static::ROUTE_MULTI_CART_INDEX, $route);

        return $routeCollection;
    }
}
