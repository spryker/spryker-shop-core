<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Plugin\Router;

use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class ErrorPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_ERROR_404 = 'error/404';
    public const ROUTE_ERROR_404_PATH = '/error/404';
    public const ROUTE_ERROR_403 = 'error/403';
    public const ROUTE_ERROR_403_PATH = '/error/403';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addError404Route($routeCollection);
        $routeCollection = $this->addError403Route($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ErrorPage\Controller\Error404Controller::indexAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addError404Route(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(static::ROUTE_ERROR_404_PATH, 'ErrorPage', 'Error404');
        $routeCollection->add(static::ROUTE_ERROR_404, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ErrorPage\Controller\Error403Controller::indexAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addError403Route(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(static::ROUTE_ERROR_403_PATH, 'ErrorPage', 'Error403');
        $routeCollection->add(static::ROUTE_ERROR_403, $route);

        return $routeCollection;
    }
}
