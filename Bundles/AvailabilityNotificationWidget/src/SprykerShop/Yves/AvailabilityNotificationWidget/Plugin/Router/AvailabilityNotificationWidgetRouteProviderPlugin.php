<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class AvailabilityNotificationWidgetRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
{
    public const ROUTE_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE = 'availability-notification/unsubscribe';
    public const ROUTE_AVAILABILITY_NOTIFICATION_SUBSCRIBE = 'availability-notification/subscribe';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $routeCollection = $this->addAvailabilityNotificationSubscribeRoute($routeCollection);
        $routeCollection = $this->addAvailabilityNotificationUnsubscribeRoute($routeCollection);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addAvailabilityNotificationSubscribeRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/availability-notification/subscribe', 'AvailabilityNotificationWidget', 'AvailabilityNotificationSubscription', 'subscribeAction');
        $routeCollection->add(static::ROUTE_AVAILABILITY_NOTIFICATION_SUBSCRIBE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addAvailabilityNotificationUnsubscribeRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/availability-notification/unsubscribe', 'AvailabilityNotificationWidget', 'AvailabilityNotificationSubscription', 'unsubscribeAction');
        $routeCollection->add(static::ROUTE_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE, $route);
        return $routeCollection;
    }
}
