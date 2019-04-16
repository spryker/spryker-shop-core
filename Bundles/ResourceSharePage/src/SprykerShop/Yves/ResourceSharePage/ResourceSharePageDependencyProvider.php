<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToResourceShareClientBridge;

class ResourceSharePageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_RESOURCE_SHARE = 'CLIENT_RESOURCE_SHARE';

    public const PLUGINS_RESOURCE_SHARE_ROUTER_STRATEGY = 'PLUGINS_RESOURCE_SHARE_ROUTER_STRATEGY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addResourceShareClient($container);
        $container = $this->addResourceShareRouterStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addResourceShareClient(Container $container): Container
    {
        $container[static::CLIENT_RESOURCE_SHARE] = function (Container $container) {
            return new ResourceSharePageToResourceShareClientBridge($container->getLocator()->resourceShare()->client());
        };

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\ResourceSharePageExtension\Dependency\Plugin\ResourceShareRouterStrategyPluginInterface[]
     */
    protected function getResourceShareRouterStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addResourceShareRouterStrategyPlugins(Container $container): Container
    {
        $container[static::PLUGINS_RESOURCE_SHARE_ROUTER_STRATEGY] = function () {
            return $this->getResourceShareRouterStrategyPlugins();
        };

        return $container;
    }
}
