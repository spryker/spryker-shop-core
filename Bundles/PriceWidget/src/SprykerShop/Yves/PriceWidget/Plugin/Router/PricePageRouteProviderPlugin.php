<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceWidget\Plugin\Router;

use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class PricePageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_PRICE_SWITCH = 'price-mode-switch';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addPriceModeSwitchRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addPriceModeSwitchRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_PRICE_SWITCH,
            $this->buildRoute('/price/mode-switch', 'PriceWidget', 'PriceModeSwitch')
        );

        return $routeCollection;
    }
}
