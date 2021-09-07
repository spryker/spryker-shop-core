<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductGroupWidget\Dependency\Client\ProductGroupWidgetToProductGroupStorageClientBridge;
use SprykerShop\Yves\ProductGroupWidget\Dependency\Client\ProductGroupWidgetToProductStorageClientBridge;

class ProductGroupWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_GROUP_STORAGE = 'CLIENT_PRODUCT_GROUP_STORAGE';
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_VIEW_EXPANDERS = 'PLUGIN_PRODUCT_VIEW_EXPANDERS';
    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_VIEW_BULK_EXPANDERS = 'PLUGINS_PRODUCT_VIEW_BULK_EXPANDERS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductGroupStorageClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addProductViewExpanderPlugins($container);
        $container = $this->addProductViewBulkExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductGroupStorageClient($container): Container
    {
        $container->set(static::CLIENT_PRODUCT_GROUP_STORAGE, function (Container $container) {
            return new ProductGroupWidgetToProductGroupStorageClientBridge($container->getLocator()->productGroupStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient($container): Container
    {
        $container->set(static::CLIENT_PRODUCT_STORAGE, function (Container $container) {
            return new ProductGroupWidgetToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductViewExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_PRODUCT_VIEW_EXPANDERS, function () {
            return $this->getProductViewExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductViewBulkExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_VIEW_BULK_EXPANDERS, function () {
            return $this->getProductViewBulkExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\ProductGroupWidgetExtension\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected function getProductViewExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \SprykerShop\Yves\ProductGroupWidgetExtension\Dependency\Plugin\ProductViewBulkExpanderPluginInterface[]
     */
    protected function getProductViewBulkExpanderPlugins(): array
    {
        return [];
    }
}
