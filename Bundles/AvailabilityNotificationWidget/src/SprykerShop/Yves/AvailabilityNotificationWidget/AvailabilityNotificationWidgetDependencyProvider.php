<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client\AvailabilityNotificationWidgetToAvailabilityNotificationClientBridge;
use SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client\AvailabilityNotificationWidgetToCustomerClientBridge;
use SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client\AvailabilityNotificationWidgetToSessionClientBridge;

class AvailabilityNotificationWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_AVAILABILITY_NOTIFICATION = 'CLIENT_AVAILABILITY_NOTIFICATION';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_SESSION = 'CLIENT_SESSION';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addAvailabilityNotificationClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addSessionClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container[static::CLIENT_SESSION] = function (Container $container) {
            return new AvailabilityNotificationWidgetToSessionClientBridge(
                $container->getLocator()->session()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAvailabilityNotificationClient(Container $container): Container
    {
        $container[static::CLIENT_AVAILABILITY_NOTIFICATION] = function (Container $container) {
            return new AvailabilityNotificationWidgetToAvailabilityNotificationClientBridge($container->getLocator()->availabilityNotification()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new AvailabilityNotificationWidgetToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }
}
