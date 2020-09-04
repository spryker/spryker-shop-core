<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductConfigurationWidget\Dependency\Client\ProductConfigurationWidgetToProductConfigurationClientBridge;

class ProductConfigurationWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_CONFIGURATION = 'CLIENT_PRODUCT_CONFIGURATION';
    public const PLUGINS_PRODUCT_CONFIGURATION_RENDER_STRATEGY = 'PLUGINS_PRODUCT_CONFIGURATION_RENDER_STRATEGY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductConfigurationRenderStrategyPlugins($container);
        $container = $this->addProductConfigurationClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductConfigurationClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_CONFIGURATION, function (Container $container) {
            return new ProductConfigurationWidgetToProductConfigurationClientBridge($container->getLocator()->productConfiguration()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductConfigurationRenderStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONFIGURATION_RENDER_STRATEGY, function () {
            return $this->getProductConfigurationRenderStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationWidgetExtension\Dependency\Plugin\ProductConfigurationRenderStrategyPluginInterface[]
     */
    protected function getProductConfigurationRenderStrategyPlugins(): array
    {
        return [];
    }
}
