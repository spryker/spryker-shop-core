<?php

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Model;

interface ProductMeasurementSalesUnitReaderInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|null
     */
    public function findProductMeasurementSalesUnitByIdProductConcrete(int $idProductConcrete): ?array;
}