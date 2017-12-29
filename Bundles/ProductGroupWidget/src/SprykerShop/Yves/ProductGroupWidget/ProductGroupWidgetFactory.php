<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductGroupWidget\Dependency\Client\ProductGroupWidgetToProductGroupStorageClientInterface;
use SprykerShop\Yves\ProductGroupWidget\Dependency\Client\ProductGroupWidgetToProductStorageClientInterface;

class ProductGroupWidgetFactory extends AbstractFactory
{

    /**
     * @return ProductGroupWidgetToProductGroupStorageClientInterface
     */
    public function getProductGroupStorageClient() : ProductGroupWidgetToProductGroupStorageClientInterface
    {
        return $this->getProvidedDependency(ProductGroupWidgetDependencyProvider::CLIENT_PRODUCT_GROUP_STORAGE);
    }

    /**
     * @return ProductGroupWidgetToProductStorageClientInterface
     */
    public function getProductStorageClient() : ProductGroupWidgetToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductGroupWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
