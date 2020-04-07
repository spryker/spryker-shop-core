<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductRelationWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductRelationWidget\Dependency\Client\ProductRelationWidgetToProductRelationStorageClientBridge;
use SprykerShop\Yves\ProductRelationWidget\Dependency\Client\ProductRelationWidgetToStoreClientBridge;

class ProductRelationWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_RELATION_STORAGE = 'CLIENT_PRODUCT_RELATION_STORAGE';
    public const CLIENT_STORE = 'CLIENT_STORE';
    public const PLUGIN_PRODUCT_DETAIL_PAGE_SIMILAR_PRODUCTS_WIDGETS = 'PLUGIN_PRODUCT_DETAIL_PAGE_SIMILAR_PRODUCTS_WIDGETS';
    public const PLUGIN_CART_PAGE_UP_SELLING_PRODUCTS_WIDGETS = 'PLUGIN_CART_PAGE_UP_SELLING_PRODUCTS_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductRelationStorageClient($container);
        $container = $this->addProductDetailPageSimilarProductsWidgetPlugins($container);
        $container = $this->addCartPageUpSellingProductsWidgetPlugins($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new ProductRelationWidgetToStoreClientBridge(
                $container->getLocator()->store()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductRelationStorageClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_RELATION_STORAGE] = function (Container $container) {
            return new ProductRelationWidgetToProductRelationStorageClientBridge($container->getLocator()->productRelationStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductDetailPageSimilarProductsWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_PRODUCT_DETAIL_PAGE_SIMILAR_PRODUCTS_WIDGETS] = function () {
            return $this->getProductDetailPageSimilarProductsWidgetPlugins();
        };

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getProductDetailPageSimilarProductsWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartPageUpSellingProductsWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_CART_PAGE_UP_SELLING_PRODUCTS_WIDGETS] = function () {
            return $this->getProductDetailPageSimilarProductsWidgetPlugins();
        };

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getCartPageUpSellingProductsWidgetPlugins(): array
    {
        return [];
    }
}
