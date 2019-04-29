<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class AgentPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_LOGIN = 'agent/login';
    public const ROUTE_LOGOUT = 'agent/logout';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addLoginRoute($routeCollection);
        $routeCollection = $this->addLogoutRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addLoginRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/login', 'AgentPage', 'Auth', 'loginAction');
        $routeCollection->add(static::ROUTE_LOGIN, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addLogoutRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/logout', 'AgentPage', 'Auth', 'logoutAction');
        $routeCollection->add(static::ROUTE_LOGOUT, $route);

        return $routeCollection;
    }
}
