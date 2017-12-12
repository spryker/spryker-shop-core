<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductSetListPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductSetListPage\Dependency\Client\ProductSetListPageToProductSetClientBridge;

class ProductSetListPageDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_PRODUCT_SET = 'CLIENT_PRODUCT_SET';
    const PLUGIN_PRODUCT_SET_LIST_PAGE_WIDGETS = 'PLUGIN_PRODUCT_SET_LIST_PAGE_WIDGETS';

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
        $container[self::CLIENT_PRODUCT_SET] = function (Container $container) {
            return new ProductSetListPageToProductSetClientBridge($container->getLocator()->productSet()->client());
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
