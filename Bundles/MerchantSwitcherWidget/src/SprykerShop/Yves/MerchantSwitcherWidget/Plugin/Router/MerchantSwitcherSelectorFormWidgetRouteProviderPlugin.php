<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class MerchantSwitcherSelectorFormWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_MERCHANT_SWITCH = 'switch-merchant';

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
        $routeCollection = $this->addMerchantSwitchRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMerchantSwitchRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_MERCHANT_SWITCH,
            $this->buildRoute('/merchant/merchant-switch', 'MerchantSwitcherWidget', 'MerchantSwitcherSelectorFormWidget', 'switchMerchant')
        );

        return $routeCollection;
    }
}
