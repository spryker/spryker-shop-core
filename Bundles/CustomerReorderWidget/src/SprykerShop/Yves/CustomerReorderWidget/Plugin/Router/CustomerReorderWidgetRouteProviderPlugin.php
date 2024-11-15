<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

/**
 * @deprecated Will be removed without replacement.
 */
class CustomerReorderWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerReorderWidget\Plugin\Router\CustomerReorderWidgetRouteProviderPlugin::ROUTE_NAME_CART_ORDER_REPEAT} instead.
     *
     * @var string
     */
    protected const ROUTE_CART_ORDER_REPEAT = 'customer/order/reorder';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_ORDER_REPEAT = 'customer/order/reorder';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerReorderWidget\Plugin\Router\CustomerReorderWidgetRouteProviderPlugin::ROUTE_NAME_CART_ORDER_ITEMS_REPEAT} instead.
     *
     * @var string
     */
    protected const ROUTE_CART_ORDER_ITEMS_REPEAT = 'customer/order/reorder-items';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_ORDER_ITEMS_REPEAT = 'customer/order/reorder-items';

    /**
     * @var string
     */
    protected const PATTERN_ID = '\d+';

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
        $routeCollection = $this->addReorderRoute($routeCollection);
        $routeCollection = $this->addReorderItemsRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CustomerReorderWidget\Controller\OrderController::reorderAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addReorderRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/order/{idSalesOrder}/reorder', 'CustomerReorderWidget', 'Order', 'reorderAction');
        $route = $route->setRequirement('idSalesOrder', static::PATTERN_ID);
        $routeCollection->add(static::ROUTE_NAME_CART_ORDER_REPEAT, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CustomerReorderWidget\Controller\OrderController::reorderItemsAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addReorderItemsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/order/reorder-items', 'CustomerReorderWidget', 'Order', 'reorderItemsAction');
        $route = $route->setMethods(['POST']);
        $routeCollection->add(static::ROUTE_NAME_CART_ORDER_ITEMS_REPEAT, $route);

        return $routeCollection;
    }
}
