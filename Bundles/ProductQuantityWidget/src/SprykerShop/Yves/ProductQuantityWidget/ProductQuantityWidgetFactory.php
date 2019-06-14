<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantityWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductQuantityWidget\Dependency\Client\ProductQuantityWidgetToProductQuantityStorageClientInterface;
use SprykerShop\Yves\ProductQuantityWidget\QuantityRestrictionReader\QuantityRestrictionReader;
use SprykerShop\Yves\ProductQuantityWidget\QuantityRestrictionReader\QuantityRestrictionReaderInterface;

class ProductQuantityWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductQuantityWidget\QuantityRestrictionReader\QuantityRestrictionReaderInterface
     */
    public function createQuantityRestrictionReader(): QuantityRestrictionReaderInterface
    {
        return new QuantityRestrictionReader(
            $this->getProductQuantityStorageClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductQuantityWidget\Dependency\Client\ProductQuantityWidgetToProductQuantityStorageClientInterface
     */
    public function getProductQuantityStorageClient(): ProductQuantityWidgetToProductQuantityStorageClientInterface
    {
        return $this->getProvidedDependency(ProductQuantityWidgetDependencyProvider::CLIENT_PRODUCT_QUANTITY_STORAGE);
    }
}
