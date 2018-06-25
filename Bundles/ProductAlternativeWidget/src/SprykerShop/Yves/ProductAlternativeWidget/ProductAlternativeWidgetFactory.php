<?php

namespace SprykerShop\Yves\ProductAlternativeWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductAlternativeStorageClientInterface;
use SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductStorageClientInterface;

class ProductAlternativeWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductAlternativeWidgetToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductAlternativeWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductAlternativeStorageClientInterface
     */
    public function getProductAlternativeStorageClient(): ProductAlternativeWidgetToProductAlternativeStorageClientInterface
    {
        return $this->getProvidedDependency(ProductAlternativeWidgetDependencyProvider::CLIENT_PRODUCT_ALTERNATIVE_STORAGE);
    }

    /**
     * @return string[]
     */
    public function getProductDetailPageProductAlternativeWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ProductAlternativeWidgetDependencyProvider::PLUGIN_PRODUCT_DETAIL_PAGE_PRODUCT_ALTERNATIVE_WIDGETS);
    }
}
