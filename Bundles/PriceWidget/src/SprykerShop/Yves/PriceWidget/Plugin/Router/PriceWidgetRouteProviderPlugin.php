<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class PriceWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_PRICE_SWITCH = 'price-mode-switch';

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
        $routeCollection = $this->addPriceModeSwitchRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPriceModeSwitchRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/price/mode-switch', 'PriceWidget', 'PriceModeSwitch', 'indexAction');
        $routeCollection->add(static::ROUTE_PRICE_SWITCH, $route);

        return $routeCollection;
    }
}
