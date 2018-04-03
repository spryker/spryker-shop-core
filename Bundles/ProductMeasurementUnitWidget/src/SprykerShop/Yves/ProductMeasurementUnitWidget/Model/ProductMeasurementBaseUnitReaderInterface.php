<?php

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Model;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

interface ProductMeasurementBaseUnitReaderInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null
     */
    public function findProductMeasurementBaseUnitByIdProductConcrete(int $idProductConcrete): ?ProductMeasurementUnitTransfer;
}