<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToAvailabilityClientInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductQuantityStorageClientInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Reader\QuantityRestrictionReader;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Reader\QuantityRestrictionReaderInterface;

class ProductMeasurementUnitWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Reader\QuantityRestrictionReaderInterface
     */
    public function createQuantityRestrictionReader(): QuantityRestrictionReaderInterface
    {
        return new QuantityRestrictionReader();
    }

    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface
     */
    public function getProductMeasurementUnitStorageClient(): ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitWidgetDependencyProvider::CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductQuantityStorageClientInterface
     */
    public function getProductQuantityStorageClient(): ProductMeasurementUnitWidgetToProductQuantityStorageClientInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitWidgetDependencyProvider::CLIENT_PRODUCT_QUANTITY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToAvailabilityClientInterface
     */
    public function getAvailabilityClient(): ProductMeasurementUnitWidgetToAvailabilityClientInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitWidgetDependencyProvider::CLIENT_AVAILABILITY);
    }
}
