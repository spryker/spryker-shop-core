<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRegistrationRequestPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class MerchantRegistrationRequestPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    protected const ROUTE_MERCHANT_REGISTRATION_REQUEST = 'merchant-registration-request';

    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addMerchantRegistrationRequestRoute($routeCollection);

        return $routeCollection;
    }

    protected function addMerchantRegistrationRequestRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/merchant-registration-request', 'MerchantRegistrationRequestPage', 'Registration');
        $routeCollection->add(static::ROUTE_MERCHANT_REGISTRATION_REQUEST, $route);

        return $routeCollection;
    }
}
