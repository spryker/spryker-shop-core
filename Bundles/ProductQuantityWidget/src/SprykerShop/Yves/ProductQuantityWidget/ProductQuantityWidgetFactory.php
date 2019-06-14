<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantityWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductQuantityWidget\Dependency\Client\ProductQuantityWidgetToProductQuantityStorageClientInterface;

class ProductQuantityWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductQuantityWidget\Dependency\Client\ProductQuantityWidgetToProductQuantityStorageClientInterface
     */
    public function getProductQuantityStorageClient(): ProductQuantityWidgetToProductQuantityStorageClientInterface
    {
        return $this->getProvidedDependency(ProductQuantityWidgetDependencyProvider::CLIENT_PRODUCT_QUANTITY_STORAGE);
    }
}
