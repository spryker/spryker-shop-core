<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class ShoppingListWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_ADD_ITEM = 'shopping-list/add-item';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addAddItemRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addAddItemRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/add-item', 'ShoppingListWidget', 'ShoppingListWidget', 'indexAction');
        $routeCollection->add(static::ROUTE_ADD_ITEM, $route);

        return $routeCollection;
    }
}
