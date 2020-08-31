<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ProductConfigurationWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_PRODUCT_CONFIGURATION_RENDERER = 'PLUGIN_PRODUCT_CONFIGURATION_RENDERER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductConfigurationRendererPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductConfigurationRendererPlugins(Container $container)
    {
        $container->set(static::PLUGIN_PRODUCT_CONFIGURATION_RENDERER, function () {
            return $this->getProductConfigurationRendererPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationWidgetExtension\Dependency\Plugin\ProductConfigurationRendererPluginInterface[]
     */
    protected function getProductConfigurationRendererPlugins()
    {
        return [];
    }
}
