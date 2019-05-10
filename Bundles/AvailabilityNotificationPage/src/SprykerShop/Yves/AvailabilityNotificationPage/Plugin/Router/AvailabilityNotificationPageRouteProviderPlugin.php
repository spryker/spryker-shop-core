<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationPage\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class AvailabilityNotificationPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE = 'availability-notification/unsubscribe-by-key';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addAvailabilityNotificationUnsubscribeRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addAvailabilityNotificationUnsubscribeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/availability-notification/unsubscribe-by-key/{subscriptionKey}', 'AvailabilityNotificationPage', 'AvailabilityNotificationPage', 'unsubscribeByKeyAction');
        $route = $route->setRequirement('subscriptionKey', '[0-9A-Za-z]{32}');
        $routeCollection->add(static::ROUTE_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE, $route);

        return $routeCollection;
    }
}
