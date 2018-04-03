<?php

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper;

use Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

class ProductMeasurementUnitMapper implements ProductMeasurementUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer $measurementUnitStorageTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $measurementUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer
     */
    public function mapProductMeasurementUnit(
        ProductMeasurementUnitStorageTransfer $measurementUnitStorageTransfer,
        ProductMeasurementUnitTransfer $measurementUnitTransfer
    ): ProductMeasurementUnitTransfer {
        $measurementUnitTransfer->fromArray(
            $measurementUnitStorageTransfer->toArray(),
            true
        );

        return $measurementUnitTransfer;
    }
}