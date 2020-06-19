<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCancelWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class OrderCancelWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_ORDER_CANCEL = 'order/cancel';

    /**
     * {@inheritDoc}
     * - Adds OrderCancelWidget module routes to RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addOrderCancelRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\OrderCancelWidget\Controller\OrderCancelController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addOrderCancelRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildPostRoute('/order/cancel', 'OrderCancelWidget', 'OrderCancel');
        $routeCollection->add(static::ROUTE_ORDER_CANCEL, $route);

        return $routeCollection;
    }
}
