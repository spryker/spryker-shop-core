<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetListPage\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class ProductSetListPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_PRODUCT_SETS = 'product-sets';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addProductSetListRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addProductSetListRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/product-sets', 'ProductSetListPage', 'List', 'indexAction');
        $routeCollection->add(static::ROUTE_PRODUCT_SETS, $route);

        return $routeCollection;
    }
}
