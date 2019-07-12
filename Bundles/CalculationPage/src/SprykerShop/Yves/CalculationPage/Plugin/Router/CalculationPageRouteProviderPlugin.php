<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CalculationPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CalculationPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_CALCULATION_DEBUG = 'calculation-debug';

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
        $routeCollection = $this->addCalculationDebugRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCalculationDebugRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/calculation/debug', 'CalculationPage', 'Debug', 'cartAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_CALCULATION_DEBUG, $route);

        return $routeCollection;
    }
}
