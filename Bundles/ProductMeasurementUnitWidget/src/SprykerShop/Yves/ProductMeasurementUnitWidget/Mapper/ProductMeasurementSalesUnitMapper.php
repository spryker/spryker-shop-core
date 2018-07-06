<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper;

use Generated\Shared\Transfer\ProductConcreteMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;

/**
 * @deprecated Use ProductMeasurementUnitStorageClientInterface instead.
 */
class ProductMeasurementSalesUnitMapper implements ProductMeasurementSalesUnitMapperInterface
{
    /**
     * @deprecated Use ProductMeasurementUnitStorageClientInterface::findProductMeasurementSalesUnitByIdProductConcrete() instead.
     *
     * @param \Generated\Shared\Transfer\ProductConcreteMeasurementSalesUnitTransfer $concreteMeasurementSalesUnitTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $measurementSalesUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function mapProductMeasurementSalesUnitTransfer(
        ProductConcreteMeasurementSalesUnitTransfer $concreteMeasurementSalesUnitTransfer,
        ProductMeasurementSalesUnitTransfer $measurementSalesUnitTransfer
    ): ProductMeasurementSalesUnitTransfer {
        $measurementSalesUnitTransfer->fromArray(
            $concreteMeasurementSalesUnitTransfer->toArray(),
            true
        );

        return $measurementSalesUnitTransfer;
    }
}
