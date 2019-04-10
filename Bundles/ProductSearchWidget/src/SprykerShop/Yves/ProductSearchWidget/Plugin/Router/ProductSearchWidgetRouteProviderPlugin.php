<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Symfony\Component\Routing\RouteCollection;

class ProductSearchWidgetRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
{
    protected const ROUTE_PRODUCT_CONCRETE_SEARCH = 'product-search/product-concrete-search';
    protected const ROUTE_PRODUCT_QUICK_ADD = 'product-quick-add';

    /**
     * {@inheritdoc}
     *
     * @param bool|null $sslEnabled
     */
    public function __construct(?bool $sslEnabled = null)
    {
        parent::__construct($sslEnabled);

        $this->allowedLocalesPattern = $this->getAllowedLocalesPattern();
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $routeCollection = $this->addCartQuickAddRoute($routeCollection);
        $routeCollection = $this->addProductConcreteSearchRoute($routeCollection);
        return $routeCollection;
    }

    /**
     * @uses ProductConcreteAddController::indexAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCartQuickAddRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/product-search/product-quick-add', 'ProductSearchWidget', 'ProductConcreteAdd', 'indexAction');
        $routeCollection->add(static::ROUTE_PRODUCT_QUICK_ADD, $route);
        return $routeCollection;
    }

    /**
     * @uses ProductConcreteSearchController::indexAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addProductConcreteSearchRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/product-search/product-concrete-search', 'ProductSearchWidget', 'ProductConcreteSearch', 'indexAction');
        $routeCollection->add(static::ROUTE_PRODUCT_CONCRETE_SEARCH, $route);
        return $routeCollection;
    }
}
