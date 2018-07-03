<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductAlternativeWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductAlternativeStorageClientInterface;
use SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductStorageClientInterface;
use SprykerShop\Yves\ProductAlternativeWidget\ProductAlternativeMapper\ProductAlternativeMapper;
use SprykerShop\Yves\ProductAlternativeWidget\ProductAlternativeMapper\ProductAlternativeMapperInterface;

class ProductAlternativeWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductAlternativeWidget\ProductAlternativeMapper\ProductAlternativeMapperInterface
     */
    public function createProductAlternativeMapper(): ProductAlternativeMapperInterface
    {
        return new ProductAlternativeMapper(
            $this->getProductAlternativeStorageClient(),
            $this->getProductStorageClient()
        );
   }
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
