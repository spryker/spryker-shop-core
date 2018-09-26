<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetListPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductSetListPage\Dependency\Client\ProductSetListPageToProductSetPageSearchClientBridge;

class ProductSetListPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_SET_PAGE_SEARCH = 'CLIENT_PRODUCT_SET_PAGE_SEARCH';
    public const PLUGIN_PRODUCT_SET_LIST_PAGE_WIDGETS = 'PLUGIN_PRODUCT_SET_LIST_PAGE_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $this->addProductSetClient($container);
        $this->addProductSetListPageWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductSetClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_SET_PAGE_SEARCH] = function (Container $container) {
            return new ProductSetListPageToProductSetPageSearchClientBridge($container->getLocator()->productSetPageSearch()->client());
        };
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductSetListPageWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_PRODUCT_SET_LIST_PAGE_WIDGETS] = function () {
            return $this->getProductSetListPageWidgetPlugins();
        };
    }

    /**
     * @return string[]
     */
    protected function getProductSetListPageWidgetPlugins(): array
    {
        return [];
    }
}
