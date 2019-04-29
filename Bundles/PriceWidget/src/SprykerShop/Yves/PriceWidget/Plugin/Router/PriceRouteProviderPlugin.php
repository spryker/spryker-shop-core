<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceWidget\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class PriceRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_PRICE_SWITCH = 'price-mode-switch';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addPriceModeSwitchRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addPriceModeSwitchRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/price/mode-switch', 'PriceWidget', 'PriceModeSwitch', 'indexAction');
        $routeCollection->add(static::ROUTE_PRICE_SWITCH, $route);

        return $routeCollection;
    }
}
