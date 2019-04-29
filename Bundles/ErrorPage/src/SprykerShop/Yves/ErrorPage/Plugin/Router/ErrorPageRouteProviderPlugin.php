<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class ErrorPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_ERROR_404 = 'error/404';
    public const ROUTE_ERROR_404_PATH = '/error/404';
    protected const ROUTE_ERROR_403 = 'error/403';
    protected const ROUTE_ERROR_403_PATH = '/error/403';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
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
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addError404Route(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/error/404', 'ErrorPage', 'Error404', 'indexAction');
        $routeCollection->add(static::ROUTE_ERROR_404, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ErrorPage\Controller\Error403Controller::indexAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addError403Route(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/error/403', 'ErrorPage', 'Error403', 'indexAction');
        $routeCollection->add(static::ROUTE_ERROR_403, $route);

        return $routeCollection;
    }
}
