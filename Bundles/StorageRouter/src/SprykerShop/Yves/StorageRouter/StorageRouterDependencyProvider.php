<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StorageRouter;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientBridge;
use SprykerShop\Yves\StorageRouter\Dependency\Client\StorageStorageRouterToStoreClientBridge;

/**
 * @method \SprykerShop\Yves\StorageRouter\StorageRouterConfig getConfig()
 */
class StorageRouterDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_URL_STORAGE = 'CLIENT_URL_STORAGE';

    /**
     * @var string
     */
    public const PLUGIN_RESOURCE_CREATORS = 'PLUGIN_RESOURCE_CREATORS';

    /**
     * @var string
     */
    public const PLUGINS_ROUTER_ENHANCER = 'PLUGINS_ROUTER_ENHANCER';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addUrlStorageClient($container);
        $container = $this->addResourceCreatorPlugins($container);
        $container = $this->addStorageRouterEnhancerPlugins($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUrlStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_URL_STORAGE, function (Container $container) {
            return new StorageRouterToUrlStorageClientBridge($container->getLocator()->urlStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addResourceCreatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_RESOURCE_CREATORS, function () {
            return $this->getResourceCreatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStorageRouterEnhancerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ROUTER_ENHANCER, function () {
            return $this->getStorageRouterEnhancerPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface>
     */
    protected function getResourceCreatorPlugins()
    {
        return [];
    }

    /**
     * @return array<\SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\StorageRouterEnhancerPluginInterface>
     */
    protected function getStorageRouterEnhancerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new StorageStorageRouterToStoreClientBridge($container->getLocator()->store()->client());
        });

        return $container;
    }
}
