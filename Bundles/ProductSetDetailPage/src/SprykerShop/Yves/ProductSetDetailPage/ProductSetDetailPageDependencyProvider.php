<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductSetDetailPage\Dependency\Client\ProductSetDetailPageToProductSetStorageClientBridge;
use SprykerShop\Yves\ProductSetDetailPage\Dependency\Client\ProductSetDetailPageToProductStorageClientBridge;

class ProductSetDetailPageDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    const CLIENT_PRODUCT_SET_STORAGE = 'CLIENT_PRODUCT_SET_STORAGE';
    const PLUGIN_PRODUCT_SET_DETAIL_PAGE_WIDGETS = 'PLUGIN_PRODUCT_SET_DETAIL_PAGE_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductStorageClient($container);
        $container = $this->addProductSetStorageClient($container);
        $container = $this->addProductSetDetailPageWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new ProductSetDetailPageToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductSetStorageClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_SET_STORAGE] = function (Container $container) {
            return new ProductSetDetailPageToProductSetStorageClientBridge($container->getLocator()->productSetStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductSetDetailPageWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_PRODUCT_SET_DETAIL_PAGE_WIDGETS] = function () {
            return $this->getProductSetDetailPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
     */
    protected function getProductSetDetailPageWidgetPlugins(): array
    {
        return [];
    }
}
