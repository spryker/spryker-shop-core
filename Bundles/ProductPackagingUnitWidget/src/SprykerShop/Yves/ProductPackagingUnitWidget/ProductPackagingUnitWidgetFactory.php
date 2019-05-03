<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Client\ProductPackagingUnitWidgetToProductMeasurementUnitStorageClientInterface;
use SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Client\ProductPackagingUnitWidgetToProductPackagingUnitStorageClientInterface;
use SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Client\ProductPackagingUnitWidgetToProductQuantityStorageClientInterface;
use SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Service\ProductPackagingUnitWidgetToUtilEncodingServiceInterface;
use SprykerShop\Yves\ProductPackagingUnitWidget\Reader\QuantityRestrictionReader;
use SprykerShop\Yves\ProductPackagingUnitWidget\Reader\QuantityRestrictionReaderInterface;

class ProductPackagingUnitWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Client\ProductPackagingUnitWidgetToProductPackagingUnitStorageClientInterface
     */
    public function getProductPackagingUnitStorageClient(): ProductPackagingUnitWidgetToProductPackagingUnitStorageClientInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitWidgetDependencyProvider::CLIENT_PRODUCT_PACKAGING_UNIT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Client\ProductPackagingUnitWidgetToProductMeasurementUnitStorageClientInterface
     */
    public function getProductMeasurementUnitStorageClient(): ProductPackagingUnitWidgetToProductMeasurementUnitStorageClientInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitWidgetDependencyProvider::CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Client\ProductPackagingUnitWidgetToProductQuantityStorageClientInterface
     */
    public function getProductQuantityStorageClient(): ProductPackagingUnitWidgetToProductQuantityStorageClientInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitWidgetDependencyProvider::CLIENT_PRODUCT_QUANTITY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Service\ProductPackagingUnitWidgetToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductPackagingUnitWidgetToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitWidgetDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \SprykerShop\Yves\ProductPackagingUnitWidget\Reader\QuantityRestrictionReaderInterface
     */
    public function createQuantityRestrictionReader(): QuantityRestrictionReaderInterface
    {
        return new QuantityRestrictionReader();
    }
}
