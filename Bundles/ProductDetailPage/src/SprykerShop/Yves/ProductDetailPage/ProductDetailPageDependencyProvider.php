<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductDetailPage\Dependency\Client\ProductDetailPageToProductClientBridge;

class ProductDetailPageDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_PRODUCT = 'CLIENT_PRODUCT';
    const PLUGIN_PRODUCT_DETAIL_PAGE_WIDGETS = 'PLUGIN_PRODUCT_DETAIL_PAGE_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductClient($container);
        $container = $this->addProductDetailPageWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT] = function (Container $container) {
            return new ProductDetailPageToProductClientBridge($container->getLocator()->product()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductDetailPageWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_PRODUCT_DETAIL_PAGE_WIDGETS] = function () {
            return $this->getProductDetailPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getProductDetailPageWidgetPlugins(): array
    {
        return [];
    }
}
