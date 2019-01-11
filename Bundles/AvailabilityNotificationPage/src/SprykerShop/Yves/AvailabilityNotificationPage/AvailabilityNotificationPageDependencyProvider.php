<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\AvailabilityNotificationPage\Dependency\Client\AvailabilityNotificationPageToAvailabilityNotificationClientBridge;
use SprykerShop\Yves\AvailabilityNotificationPage\Dependency\Client\AvailabilityNotificationPageToLocaleClientBridge;
use SprykerShop\Yves\AvailabilityNotificationPage\Dependency\Client\AvailabilityNotificationPageToProductStorageClientBridge;

class AvailabilityNotificationPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_AVAILABILITY_NOTIFICATION = 'CLIENT_AVAILABILITY_NOTIFICATION';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addAvailabilityNotificationClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addLocaleClient($container);

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
            return new AvailabilityNotificationPageToAvailabilityNotificationClientBridge($container->getLocator()->availabilityNotification()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new AvailabilityNotificationPageToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new AvailabilityNotificationPageToLocaleClientBridge($container->getLocator()->locale()->client());
        };

        return $container;
    }
}
