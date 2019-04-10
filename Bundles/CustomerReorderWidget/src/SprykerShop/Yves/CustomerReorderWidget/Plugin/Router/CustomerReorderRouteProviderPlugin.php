<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CustomerReorderRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
{
    protected const ROUTE_CART_ORDER_REPEAT = 'customer/order/reorder';
    protected const ROUTE_CART_ORDER_ITEMS_REPEAT = 'customer/order/reorder-items';
    protected const PATTERN_ID = '\d+';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $routeCollection = $this->addReorderRoute($routeCollection);
        $routeCollection = $this->addReorderItemsRoute($routeCollection);
        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CustomerReorderWidget\Controller\OrderController::reorderAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addReorderRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/customer/order/{idSalesOrder}/reorder', 'CustomerReorderWidget', 'Order', 'reorderAction');
        $route = $route->assert('idSalesOrder', static::PATTERN_ID);
        $routeCollection->add(static::ROUTE_CART_ORDER_REPEAT, $route);
        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CustomerReorderWidget\Controller\OrderController::reorderItemsAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addReorderItemsRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/customer/order/reorder-items', 'CustomerReorderWidget', 'Order', 'reorderItemsAction');
        $route = $route->method('POST');
        $routeCollection->add(static::ROUTE_CART_ORDER_ITEMS_REPEAT, $route);
        return $routeCollection;
    }
}
