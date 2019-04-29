<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class AvailabilityNotificationWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE = 'availability-notification/unsubscribe';
    public const ROUTE_AVAILABILITY_NOTIFICATION_SUBSCRIBE = 'availability-notification/subscribe';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addAvailabilityNotificationSubscribeRoute($routeCollection);
        $routeCollection = $this->addAvailabilityNotificationUnsubscribeRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addAvailabilityNotificationSubscribeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/availability-notification/subscribe', 'AvailabilityNotificationWidget', 'AvailabilityNotificationSubscription', 'subscribeAction');
        $routeCollection->add(static::ROUTE_AVAILABILITY_NOTIFICATION_SUBSCRIBE, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addAvailabilityNotificationUnsubscribeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/availability-notification/unsubscribe', 'AvailabilityNotificationWidget', 'AvailabilityNotificationSubscription', 'unsubscribeAction');
        $routeCollection->add(static::ROUTE_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE, $route);

        return $routeCollection;
    }
}
