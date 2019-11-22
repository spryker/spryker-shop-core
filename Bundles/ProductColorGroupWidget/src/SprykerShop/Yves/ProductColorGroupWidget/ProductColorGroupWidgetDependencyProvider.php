<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductColorGroupWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ProductColorGroupWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_PRODUCT_VIEW_EXPANDERS = 'PLUGIN_PRODUCT_VIEW_EXPANDERS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductViewExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductViewExpanderPlugins(Container $container)
    {
        $container->set(static::PLUGIN_PRODUCT_VIEW_EXPANDERS, function () {
            return $this->getProductViewExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\ProductColorGroupWidgetExtension\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected function getProductViewExpanderPlugins(): array
    {
        return [];
    }
}
