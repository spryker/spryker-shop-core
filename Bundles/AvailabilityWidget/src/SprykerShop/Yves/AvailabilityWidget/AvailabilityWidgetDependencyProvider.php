<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class AvailabilityWidgetDependencyProvider extends AbstractBundleDependencyProvider
{

    public const CLIENT_AVAILABILITY_STORAGE = 'CLIENT_AVAILABILITY_STORAGE';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addAvailabilityStorageClient($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addAvailabilityStorageClient(Container $container)
    {
        $container[static::CLIENT_AVAILABILITY_STORAGE] = function (Container $container) {
            return $container->getLocator()->availabilityStorage()->client();
        };

        return $container;
    }

}
