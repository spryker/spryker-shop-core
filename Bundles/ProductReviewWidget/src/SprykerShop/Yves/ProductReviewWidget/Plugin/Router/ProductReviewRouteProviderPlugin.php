<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class ProductReviewRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
{
    public const ROUTE_PRODUCT_REVIEW_INDEX = 'product-review/index';
    public const ROUTE_PRODUCT_REVIEW_SUBMIT = 'product-review/submit';
    public const ROUTE_PRODUCT_REVIEW_CREATE = 'product-review/create';

    public const ID_ABSTRACT_PRODUCT_REGEX = '[1-9][0-9]*';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $routeCollection = $this->addProductReviewRoute($routeCollection);
        $routeCollection = $this->addProductReviewSubmitRoute($routeCollection);
        $routeCollection = $this->addProductReviewCreateRoute($routeCollection);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addProductReviewRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/product-review/index/{idProductAbstract}', 'ProductReviewWidget', 'Index', 'indexAction');
        $route = $route->assert('idProductAbstract', static::ID_ABSTRACT_PRODUCT_REGEX);
        $routeCollection->add(static::ROUTE_PRODUCT_REVIEW_INDEX, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addProductReviewSubmitRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/product-review/submit/{idProductAbstract}', 'ProductReviewWidget', 'Submit', 'indexAction');
        $route = $route->assert('idProductAbstract', static::ID_ABSTRACT_PRODUCT_REGEX);
        $routeCollection->add(static::ROUTE_PRODUCT_REVIEW_SUBMIT, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addProductReviewCreateRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/product-review/create/{idProductAbstract}', 'ProductReviewWidget', 'Create', 'indexAction');
        $route = $route->assert('idProductAbstract', static::ID_ABSTRACT_PRODUCT_REGEX);
        $routeCollection->add(static::ROUTE_PRODUCT_REVIEW_CREATE, $route);
        return $routeCollection;
    }
}
