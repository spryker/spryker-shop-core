<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CurrencyWidget\Plugin\Router;

use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class CurrencyWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_CURRENCY_SWITCH = 'currency-switch';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addCurrencySwitchRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCurrencySwitchRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_CURRENCY_SWITCH,
            $this->buildRoute('/currency/switch', 'CurrencyWidget', 'CurrencySwitch')
        );

        return $routeCollection;
    }
}
