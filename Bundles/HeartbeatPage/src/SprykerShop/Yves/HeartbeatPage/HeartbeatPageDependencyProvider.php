<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HeartbeatPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToSearchClientBridge;
use SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToSessionClientBridge;
use SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToStorageClientBridge;

class HeartbeatPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';
    public const CLIENT_SESSION = 'CLIENT_SESSION';
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addSearchClient($container);
        $container = $this->addSessionClient($container);
        $container = $this->addStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSearchClient(Container $container): Container
    {
        $container[self::CLIENT_SEARCH] = function (Container $container) {
            return new HeartbeatPageToSearchClientBridge($container->getLocator()->search()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container[self::CLIENT_SESSION] = function (Container $container) {
            return new HeartbeatPageToSessionClientBridge($container->getLocator()->session()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container[self::CLIENT_STORAGE] = function (Container $container) {
            return new HeartbeatPageToStorageClientBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }
}
