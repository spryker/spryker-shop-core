<?php

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Model;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

interface ProductMeasurementUnitReaderInterface
{
    /**
     * @param int $idProductMeasurementUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null
     */
    public function findProductMeasurementUnit(int $idProductMeasurementUnit): ?ProductMeasurementUnitTransfer;
}