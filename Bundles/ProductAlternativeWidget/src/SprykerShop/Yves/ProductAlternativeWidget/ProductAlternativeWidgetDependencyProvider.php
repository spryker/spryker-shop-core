<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductAlternativeWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductAlternativeStorageClientBridge;

class ProductAlternativeWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_ALTERNATIVE_STORAGE = 'CLIENT_PRODUCT_ALTERNATIVE_STORAGE';
    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_DETAIL_PAGE_PRODUCT_ALTERNATIVE_WIDGETS = 'PLUGIN_PRODUCT_DETAIL_PAGE_PRODUCT_ALTERNATIVE_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductAlternativeStorageClient($container);
        $container = $this->addProductDetailPageProductAlternativeWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductAlternativeStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_ALTERNATIVE_STORAGE, function (Container $container) {
            return new ProductAlternativeWidgetToProductAlternativeStorageClientBridge(
                $container->getLocator()->productAlternativeStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductDetailPageProductAlternativeWidgetPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_PRODUCT_DETAIL_PAGE_PRODUCT_ALTERNATIVE_WIDGETS, function () {
            return $this->getProductDetailPageProductAlternativeWidgetPlugins();
        });

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return array<string>
     */
    protected function getProductDetailPageProductAlternativeWidgetPlugins(): array
    {
        return [];
    }
}
