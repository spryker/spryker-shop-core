<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductNewPage\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class ProductNewPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_NEW_PRODUCTS = 'new-products';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addNewProductsRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addNewProductsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/new{categoryPath}', 'ProductNewPage', 'NewProducts', 'indexAction');
        $route = $route->setRequirement('categoryPath', '\/.+');
        $route = $route->setDefault('categoryPath', null);

        $routeCollection->add(static::ROUTE_NEW_PRODUCTS, $route);

        return $routeCollection;
    }
}
