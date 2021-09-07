<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopRouter;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\ShopRouter\Dependency\Client\ShopRouterToUrlStorageClientBridge;

/**
 * @deprecated Use `spryker/router` instead.
 * @deprecated Use `spryker-shop/storage-router` instead.
 */
class ShopRouterDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_URL_STORAGE = 'CLIENT_URL_STORAGE';

    /**
     * @deprecated Will be removed without replacement.
     * @var string
     */
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';

    /**
     * @var string
     */
    public const PLUGIN_RESOURCE_CREATORS = 'PLUGIN_RESOURCE_CREATORS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addUrlStorageClient($container);
        $container = $this->addApplicationPlugin($container);
        $container = $this->addResourceCreatorPlugins($container);

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplicationPlugin(Container $container)
    {
        $container->set(static::PLUGIN_APPLICATION, function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        });

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
            return new ShopRouterToUrlStorageClientBridge($container->getLocator()->urlStorage()->client());
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
     * @return \SprykerShop\Yves\ShopRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface[]
     */
    protected function getResourceCreatorPlugins()
    {
        return [];
    }
}
