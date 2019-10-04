<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\AvailabilityNotificationPage\Plugin\Router\AvailabilityNotificationPageRouteProviderPlugin` instead.
 */
class AvailabilityNotificationPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE = 'availability-notification/unsubscribe-by-key';
    public const SUBSCRIPTION_KEY_PATTERN = '[0-9A-Za-z]{32}';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addAvailabilityNotificationUnsubscribeRoute();
    }

    /**
     * @return $this
     */
    protected function addAvailabilityNotificationUnsubscribeRoute()
    {
        $this->createGetController('/{notificationAvailability}/unsubscribe-by-key/{subscriptionKey}', static::ROUTE_AVAILABILITY_NOTIFICATION_UNSUBSCRIBE, 'AvailabilityNotificationPage', 'AvailabilityNotificationPage', 'unsubscribeByKey')
            ->assert('notificationAvailability', $this->getAllowedLocalesPattern() . 'availability-notification|availability-notification')
            ->assert('subscriptionKey', static::SUBSCRIPTION_KEY_PATTERN)
            ->value('notificationAvailability', 'availability-notification');

        return $this;
    }
}
