<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class ErrorPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\ErrorPage\Plugin\Router\ErrorPageRouteProviderPlugin::ROUTE_NAME_ERROR_404} instead.
     */
    protected const ROUTE_ERROR_404 = 'error/404';
    public const ROUTE_NAME_ERROR_404 = 'error/404';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\ErrorPage\Plugin\Router\ErrorPageRouteProviderPlugin::ROUTE_NAME_ERROR_404_PATH} instead.
     */
    protected const ROUTE_ERROR_404_PATH = '/error/404';
    public const ROUTE_NAME_ERROR_404_PATH = '/error/404';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\ErrorPage\Plugin\Router\ErrorPageRouteProviderPlugin::ROUTE_NAME_ERROR_403} instead.
     */
    protected const ROUTE_ERROR_403 = 'error/403';
    public const ROUTE_NAME_ERROR_403 = 'error/403';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\ErrorPage\Plugin\Router\ErrorPageRouteProviderPlugin::ROUTE_NAME_ERROR_403_PATH} instead.
     */
    protected const ROUTE_ERROR_403_PATH = '/error/403';
    public const ROUTE_NAME_ERROR_403_PATH = '/error/403';

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
        $routeCollection = $this->addError404Route($routeCollection);
        $routeCollection = $this->addError403Route($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ErrorPage\Controller\Error404Controller::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addError404Route(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/error/404', 'ErrorPage', 'Error404', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_ERROR_404, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ErrorPage\Controller\Error403Controller::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addError403Route(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/error/403', 'ErrorPage', 'Error403', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_ERROR_403, $route);

        return $routeCollection;
    }
}
