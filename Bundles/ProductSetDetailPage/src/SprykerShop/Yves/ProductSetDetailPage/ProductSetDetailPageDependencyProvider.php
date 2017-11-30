<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductSetDetailPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductDetailPage\Plugin\StorageProductMapperPlugin;

class ProductSetDetailPageDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_CART = 'CLIENT_CART';
    const CLIENT_PRODUCT = 'CLIENT_PRODUCT';
    const CLIENT_PRODUCT_SET = 'CLIENT_PRODUCT_SET';

    const PLUGIN_STORAGE_PRODUCT_MAPPER = 'PLUGIN_STORAGE_PRODUCT_MAPPER';
    const PLUGIN_PRODUCT_SET_DETAIL_PAGE_WIDGETS = 'PLUGIN_PRODUCT_SET_DETAIL_PAGE_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $this->addCartClient($container);
        $this->addProductClient($container);
        $this->addProductSetClient($container);

        $this->addStorageProductMapperPlugin($container);
        $this->addProductSetDetailPageWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addCartClient(Container $container)
    {
        $container[self::CLIENT_CART] = function (Container $container) {
            return $container->getLocator()->cart()->client();
        };
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->client();
        };
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductSetClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_SET] = function (Container $container) {
            return $container->getLocator()->productSet()->client();
        };
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addStorageProductMapperPlugin(Container $container)
    {
        $container[self::PLUGIN_STORAGE_PRODUCT_MAPPER] = function (Container $container) {
            return new StorageProductMapperPlugin();
        };
    }

    protected function addProductSetDetailPageWidgetPlugins($container)
    {
        $container[self::PLUGIN_PRODUCT_SET_DETAIL_PAGE_WIDGETS] = function (Container $container) {
            return $this->getProductSetDetailPageWidgetPlugins($container);
        };
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return string[]
     */
    protected function getProductSetDetailPageWidgetPlugins(Container $container): array
    {
        return [];
    }
}
