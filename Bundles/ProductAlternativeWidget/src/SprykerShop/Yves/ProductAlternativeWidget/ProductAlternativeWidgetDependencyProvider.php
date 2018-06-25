<?php

namespace SprykerShop\Yves\ProductAlternativeWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductAlternativeStorageClientBridge;
use SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductStorageClientBridge;

class ProductAlternativeWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_ALTERNATIVE_STORAGE = 'CLIENT_PRODUCT_ALTERNATIVE_STORAGE';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
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
        $container = $this->addProductStorageClient($container);
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
        $container[static::CLIENT_PRODUCT_ALTERNATIVE_STORAGE] = function (Container $container) {
            return new ProductAlternativeWidgetToProductAlternativeStorageClientBridge(
                $container->getLocator()->productAlternativeStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new ProductAlternativeWidgetToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductDetailPageProductAlternativeWidgetPlugins(Container $container): Container
    {
        $container[static::PLUGIN_PRODUCT_DETAIL_PAGE_PRODUCT_ALTERNATIVE_WIDGETS] = function () {
            return $this->getProductDetailPageProductAlternativeWidgetPlugins();
        };

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getProductDetailPageProductAlternativeWidgetPlugins(): array
    {
        return [];
    }
}
