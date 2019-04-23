<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HeartbeatPage\Plugin\Router;

use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class HeartbeatPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_HEARTBEAT = 'heartbeat';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addHeartbeatRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addHeartbeatRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/heartbeat', 'HeartbeatPage', 'Heartbeat', 'indexAction');
        $routeCollection->add(static::ROUTE_HEARTBEAT, $route);

        return $routeCollection;
    }
}
