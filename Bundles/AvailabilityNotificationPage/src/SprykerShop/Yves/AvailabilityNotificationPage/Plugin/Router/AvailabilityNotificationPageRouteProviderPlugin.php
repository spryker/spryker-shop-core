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
    protected const ROUTE_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE = 'availability-notification/unsubscribe-by-key';
    protected const SUBSCRIPTION_KEY_PATTERN = '[0-9A-Za-z]{32}';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
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
        $route = $route->setRequirement('subscriptionKey', static::SUBSCRIPTION_KEY_PATTERN);
        $routeCollection->add(static::ROUTE_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE, $route);

        return $routeCollection;
    }
}
