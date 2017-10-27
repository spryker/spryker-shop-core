<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductRelationWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductWidget\Plugin\ProductRelationWidget\ProductWidgetPlugin;

class ProductRelationWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const PLUGIN_PRODUCT_DETAIL_PAGE_SIMILAR_PRODUCTS_WIDGETS = 'PLUGIN_PRODUCT_DETAIL_PAGE_SIMILAR_PRODUCTS_WIDGETS';
    const PLUGIN_CART_PAGE_UP_SELLING_PRODUCTS_WIDGETS = 'PLUGIN_CART_PAGE_UP_SELLING_PRODUCTS_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductDetailPageSimilarProductsWidgetPlugins($container);
        $container = $this->addCartPageUpSellingProductsWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductDetailPageSimilarProductsWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_PRODUCT_DETAIL_PAGE_SIMILAR_PRODUCTS_WIDGETS] = function (Container $container) {
            return $this->getProductDetailPageSimilarProductsWidgetPlugins($container);
        };

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return string[]
     */
    protected function getProductDetailPageSimilarProductsWidgetPlugins(Container $container): array
    {
        // TODO: move this to project level
        return [
            ProductWidgetPlugin::class,
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartPageUpSellingProductsWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_CART_PAGE_UP_SELLING_PRODUCTS_WIDGETS] = function (Container $container) {
            return $this->getProductDetailPageSimilarProductsWidgetPlugins($container);
        };

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return string[]
     */
    protected function getCartPageUpSellingProductsWidgetPlugins(Container $container): array
    {
        // TODO: move this to project level
        return [
            ProductWidgetPlugin::class,
        ];
    }
}
