<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\AvailabilityWidget\Dependency\Client\AvailabilityWidgetToAvailabilityStorageClientBridge;

class AvailabilityWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_AVAILABILITY_STORAGE = 'CLIENT_AVAILABILITY_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addAvailabilityStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAvailabilityStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_AVAILABILITY_STORAGE, function (Container $container) {
            return new AvailabilityWidgetToAvailabilityStorageClientBridge(
                $container->getLocator()->availabilityStorage()->client(),
            );
        });

        return $container;
    }
}
