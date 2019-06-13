<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantityRestrictionWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductQuantityRestrictionWidget\Dependency\Client\ProductQuantityRestrictionWidgetToProductQuantityStorageClientInterface;
use SprykerShop\Yves\ProductQuantityRestrictionWidget\Dependency\Service\ProductQuantityRestrictionWidgetToProductQuantityServiceInterface;
use SprykerShop\Yves\ProductQuantityRestrictionWidget\QuantityRestrictionReader\QuantityRestrictionReader;
use SprykerShop\Yves\ProductQuantityRestrictionWidget\QuantityRestrictionReader\QuantityRestrictionReaderInterface;

class ProductQuantityRestrictionWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductQuantityRestrictionWidget\QuantityRestrictionReader\QuantityRestrictionReaderInterface
     */
    public function createQuantityRestrictionReader(): QuantityRestrictionReaderInterface
    {
        return new QuantityRestrictionReader(
            $this->getProductQuantityStorageClient(),
            $this->getProductQuantityService()
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductQuantityRestrictionWidget\Dependency\Client\ProductQuantityRestrictionWidgetToProductQuantityStorageClientInterface
     */
    public function getProductQuantityStorageClient(): ProductQuantityRestrictionWidgetToProductQuantityStorageClientInterface
    {
        return $this->getProvidedDependency(ProductQuantityRestrictionWidgetDependencyProvider::CLIENT_PRODUCT_QUANTITY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductQuantityRestrictionWidget\Dependency\Service\ProductQuantityRestrictionWidgetToProductQuantityServiceInterface
     */
    public function getProductQuantityService(): ProductQuantityRestrictionWidgetToProductQuantityServiceInterface
    {
        return $this->getProvidedDependency(ProductQuantityRestrictionWidgetDependencyProvider::SERVICE_PRODUCT_QUANTITY);
    }
}
