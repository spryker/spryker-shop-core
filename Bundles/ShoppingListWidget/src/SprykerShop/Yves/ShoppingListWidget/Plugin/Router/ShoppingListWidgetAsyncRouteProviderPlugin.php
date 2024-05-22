<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class ShoppingListWidgetAsyncRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_SHOPPING_LIST_ASYNC_CREATE_FROM_CART = 'shopping-list/async/create-from-cart';

    /**
     * @var string
     */
    public const ROUTE_NAME_SHOPPING_LIST_ASYNC_CREATE_FROM_CART_VIEW = 'shopping-list/async/create-from-cart/view';

    /**
     * @var string
     */
    public const ROUTE_NAME_SHOPPING_LIST_ASYNC_NAVIGATION_WIDGET_VIEW = 'shopping-list/async/navigation-widget/view';

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
        $routeCollection = $this->addShoppingListAsyncCreateFromCartRoute($routeCollection);
        $routeCollection = $this->addShoppingListAsyncCreateFromCartViewRoute($routeCollection);
        $routeCollection = $this->addShoppingListAsyncNavigationWidgetViewRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ShoppingListWidget\Controller\CartToShoppingListAsyncController::createFromCartAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListAsyncCreateFromCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/async/create-from-cart', 'ShoppingListWidget', 'CartToShoppingListAsync', 'createFromCartAction');
        $routeCollection->add(static::ROUTE_NAME_SHOPPING_LIST_ASYNC_CREATE_FROM_CART, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ShoppingListWidget\Controller\CartToShoppingListAsyncController::viewAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListAsyncCreateFromCartViewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/async/create-from-cart/view', 'ShoppingListWidget', 'CartToShoppingListAsync', 'viewAction');
        $routeCollection->add(static::ROUTE_NAME_SHOPPING_LIST_ASYNC_CREATE_FROM_CART_VIEW, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ShoppingListWidget\Controller\ShoppingListWidgetAsyncController::navigationWidgetViewAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListAsyncNavigationWidgetViewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/async/navigation-widget/view', 'ShoppingListWidget', 'ShoppingListWidgetAsync', 'navigationWidgetViewAction');
        $routeCollection->add(static::ROUTE_NAME_SHOPPING_LIST_ASYNC_NAVIGATION_WIDGET_VIEW, $route);

        return $routeCollection;
    }
}
