<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class ProductReviewWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_PRODUCT_REVIEW_INDEX = 'product-review/index';
    protected const ROUTE_PRODUCT_REVIEW_CREATE = 'product-review/create';

    protected const ID_ABSTRACT_PRODUCT_REGEX = '[1-9][0-9]*';

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
        $routeCollection = $this->addProductReviewRoute($routeCollection);
        $routeCollection = $this->addProductReviewCreateRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addProductReviewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/product-review/index/{idProductAbstract}', 'ProductReviewWidget', 'Index', 'indexAction');
        $route = $route->setRequirement('idProductAbstract', static::ID_ABSTRACT_PRODUCT_REGEX);
        $routeCollection->add(static::ROUTE_PRODUCT_REVIEW_INDEX, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addProductReviewCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/product-review/create/{idProductAbstract}', 'ProductReviewWidget', 'Create', 'indexAction');
        $route = $route->setRequirement('idProductAbstract', static::ID_ABSTRACT_PRODUCT_REGEX);
        $routeCollection->add(static::ROUTE_PRODUCT_REVIEW_CREATE, $route);

        return $routeCollection;
    }
}
