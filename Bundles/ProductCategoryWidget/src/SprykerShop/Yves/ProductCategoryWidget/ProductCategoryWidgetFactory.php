<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductCategoryWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductCategoryWidget\Dependency\Client\ProductCategoryWidgetToProductCategoryStorageClientInterface;

class ProductCategoryWidgetFactory extends AbstractFactory
{
    /**
     * @return ProductCategoryWidgetToProductCategoryStorageClientInterface
     */
    public function getProductCategoryStorageClient(): ProductCategoryWidgetToProductCategoryStorageClientInterface
    {
        return $this->getProvidedDependency(ProductCategoryWidgetDependencyProvider::CLIENT_PRODUCT_CATEGORY_STORAGE);
    }
}
