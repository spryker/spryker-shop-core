<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class ProductSearchWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_PRODUCT_CONCRETE_SEARCH = 'product-search/product-concrete-search';
    protected const ROUTE_PRODUCT_QUICK_ADD = 'product-quick-add';

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
        $routeCollection = $this->addCartQuickAddRoute($routeCollection);
        $routeCollection = $this->addProductConcreteSearchRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses ProductConcreteAddController::indexAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addCartQuickAddRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/product-search/product-quick-add', 'ProductSearchWidget', 'ProductConcreteAdd', 'indexAction');
        $routeCollection->add(static::ROUTE_PRODUCT_QUICK_ADD, $route);

        return $routeCollection;
    }

    /**
     * @uses ProductConcreteSearchController::indexAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addProductConcreteSearchRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/product-search/product-concrete-search', 'ProductSearchWidget', 'ProductConcreteSearch', 'indexAction');
        $routeCollection->add(static::ROUTE_PRODUCT_CONCRETE_SEARCH, $route);

        return $routeCollection;
    }
}
