<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementSalesUnitMapper;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementSalesUnitMapperInterface;

class ProductMeasurementUnitWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface
     */
    public function getProductMeasurementUnitStorageClient(): ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitWidgetDependencyProvider::CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementSalesUnitMapperInterface
     */
    public function createProductMeasurementSalesUnitMapper(): ProductMeasurementSalesUnitMapperInterface
    {
        return new ProductMeasurementSalesUnitMapper();
    }
}
