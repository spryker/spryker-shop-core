<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantity;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductQuantity\Dependency\Client\ProductQuantityToProductQuantityStorageClientInterface;

class ProductQuantityFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductQuantity\Dependency\Client\ProductQuantityToProductQuantityStorageClientInterface
     */
    public function getProductQuantityStorageClient(): ProductQuantityToProductQuantityStorageClientInterface
    {
        return $this->getProvidedDependency(ProductQuantityDependencyProvider::CLIENT_PRODUCT_QUANTITY_STORAGE);
    }
}
