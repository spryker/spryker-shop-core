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
     *
     * @var string
     */
    protected const ROUTE_ERROR_404 = 'error/404';

    /**
     * @var string
     */
    public const ROUTE_NAME_ERROR_404 = 'error/404';

    /**
     * @var string
     */
    public const ROUTE_NAME_ERROR_CACHEABLE_404 = 'error-page/404';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\ErrorPage\Plugin\Router\ErrorPageRouteProviderPlugin::ROUTE_NAME_ERROR_404_PATH} instead.
     *
     * @var string
     */
    protected const ROUTE_ERROR_404_PATH = '/error/404';

    /**
     * @var string
     */
    public const ROUTE_NAME_ERROR_404_PATH = '/error/404';

    /**
     * @var string
     */
    public const ROUTE_NAME_ERROR_CACHEABLE_404_PATH = '/error-page/404';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\ErrorPage\Plugin\Router\ErrorPageRouteProviderPlugin::ROUTE_NAME_ERROR_403} instead.
     *
     * @var string
     */
    protected const ROUTE_ERROR_403 = 'error/403';

    /**
     * @var string
     */
    public const ROUTE_NAME_ERROR_403 = 'error/403';

    /**
     * @var string
     */
    public const ROUTE_NAME_ERROR_CACHEABLE_403 = 'error-page/403';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\ErrorPage\Plugin\Router\ErrorPageRouteProviderPlugin::ROUTE_NAME_ERROR_403_PATH} instead.
     *
     * @var string
     */
    protected const ROUTE_ERROR_403_PATH = '/error/403';

    /**
     * @var string
     */
    public const ROUTE_NAME_ERROR_403_PATH = '/error/403';

    /**
     * @var string
     */
    public const ROUTE_NAME_ERROR_CACHEABLE_403_PATH = '/error-page/403';

    /**
     * @var string
     */
    public const ROUTE_NAME_ERROR_429 = 'error/429';

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

        $routeCollection = $this->addError404CacheableRoute($routeCollection);
        $routeCollection = $this->addError403CacheableRoute($routeCollection);

        $routeCollection = $this->addError429Route($routeCollection);

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
     * @uses \SprykerShop\Yves\ErrorPage\Controller\Error404Controller::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addError404CacheableRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(static::ROUTE_NAME_ERROR_CACHEABLE_404_PATH, 'ErrorPage', 'Error404Cacheable', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_ERROR_CACHEABLE_404, $route);

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

    /**
     * @uses \SprykerShop\Yves\ErrorPage\Controller\Error403Controller::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addError403CacheableRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(static::ROUTE_NAME_ERROR_CACHEABLE_403_PATH, 'ErrorPage', 'Error403Cacheable', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_ERROR_CACHEABLE_403, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ErrorPage\Controller\Error429Controller::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addError429Route(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/error/429', 'ErrorPage', 'Error429', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_ERROR_429, $route);

        return $routeCollection;
    }
}
