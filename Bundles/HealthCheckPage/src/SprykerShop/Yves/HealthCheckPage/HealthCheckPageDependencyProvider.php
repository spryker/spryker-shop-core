<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HealthCheckPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\HealthCheckPage\Dependency\Client\HealthCheckPageToHealthCheckClientBridge;

class HealthCheckPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_HEALTH_CHECK = 'CLIENT_HEALTH_CHECK';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addHealthCheckClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addHealthCheckClient(Container $container): Container
    {
        $container->set(static::CLIENT_HEALTH_CHECK, function (Container $container) {
            return new HealthCheckPageToHealthCheckClientBridge(
                $container->getLocator()->healthCheck()->client()
            );
        });

        return $container;
    }
}
