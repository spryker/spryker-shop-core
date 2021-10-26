<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class MerchantSwitcherWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\MerchantSwitcherWidget\Plugin\Router\MerchantSwitcherWidgetRouteProviderPlugin::ROUTE_NAME_SWITCH_MERCHANT} instead.
     *
     * @var string
     */
    protected const ROUTE_SWITCH_MERCHANT = 'switch-merchant';

    /**
     * @var string
     */
    public const ROUTE_NAME_SWITCH_MERCHANT = 'switch-merchant';

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
        $routeCollection = $this->addSwitchMerchantRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\MerchantSwitcherWidget\Controller\MerchantSwitcherController::switchMerchantAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSwitchMerchantRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::ROUTE_NAME_SWITCH_MERCHANT,
            $this->buildPostRoute('/merchant/switch', 'MerchantSwitcherWidget', 'MerchantSwitcher', 'switchMerchant'),
        );

        return $routeCollection;
    }
}
