<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HeartbeatPage\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class HeartbeatPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_HEARTBEAT = 'heartbeat';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addHeartbeatRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addHeartbeatRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/heartbeat', 'HeartbeatPage', 'Heartbeat', 'indexAction');
        $routeCollection->add(static::ROUTE_HEARTBEAT, $route);

        return $routeCollection;
    }
}
