<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopRouter;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\ShopRouter\Dependency\Client\ShopRouterToCollectorClientBridge;

class ShopRouterDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_COLLECTOR = 'CLIENT_COLLECTOR';
    const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';
    const PLUGIN_RESOURCE_CREATORS = 'PLUGIN_RESOURCE_CREATORS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCollectorClient($container);
        $container = $this->addApplicationPlugin($container);
        $container = $this->addResourceCreatorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplicationPlugin(Container $container)
    {
        $container[self::PLUGIN_APPLICATION] = function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCollectorClient(Container $container): Container
    {
        $container[self::CLIENT_COLLECTOR] = function (Container $container) {
            return new ShopRouterToCollectorClientBridge($container->getLocator()->collector()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addResourceCreatorPlugins(Container $container): Container
    {
        $container[self::PLUGIN_RESOURCE_CREATORS] = function () {
            return $this->getResourceCreatorPlugins();
        };

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\ShopRouter\Dependency\Plugin\ResourceCreatorPluginInterface[]
     */
    protected function getResourceCreatorPlugins()
    {
        return [];
    }
}
