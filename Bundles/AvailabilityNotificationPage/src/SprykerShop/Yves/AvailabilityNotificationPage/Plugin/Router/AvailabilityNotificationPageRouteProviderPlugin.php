<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class AvailabilityNotificationPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\AvailabilityNotificationPage\Plugin\Router\AvailabilityNotificationPageRouteProviderPlugin::ROUTE_NAME_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE} instead.
     *
     * @var string
     */
    protected const ROUTE_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE = 'availability-notification/unsubscribe-by-key';

    /**
     * @var string
     */
    public const ROUTE_NAME_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE = 'availability-notification/unsubscribe-by-key';

    /**
     * @var string
     */
    protected const SUBSCRIPTION_KEY_PATTERN = '[0-9A-Za-z]{32}';

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
        $routeCollection = $this->addAvailabilityNotificationUnsubscribeRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAvailabilityNotificationUnsubscribeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/availability-notification/unsubscribe-by-key/{subscriptionKey}', 'AvailabilityNotificationPage', 'AvailabilityNotificationPage', 'unsubscribeByKeyAction');
        $route = $route->setRequirement('subscriptionKey', static::SUBSCRIPTION_KEY_PATTERN);
        $routeCollection->add(static::ROUTE_NAME_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE, $route);

        return $routeCollection;
    }
}
