<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CurrencyWidget\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class CurrencyWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_CART = 'currency-switch';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addCurrencySwitchRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addCurrencySwitchRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/currency/switch', 'CurrencyWidget', 'CurrencySwitch', 'indexAction');
        $routeCollection->add(static::ROUTE_CART, $route);

        return $routeCollection;
    }
}
