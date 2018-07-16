<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Model;

/**
 * @deprecated Use ProductMeasurementUnitStorageClientInterface instead.
 */
interface ProductMeasurementSalesUnitReaderInterface
{
    /**
     * @see ProductMeasurementUnitStorageClientInterface::findProductMeasurementSalesUnitByIdProduct()
     *
     * @deprecated Use ProductMeasurementUnitStorageClientInterface::findProductMeasurementSalesUnitByIdProduct() instead.
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|null
     */
    public function findProductMeasurementSalesUnitByIdProductConcrete(int $idProductConcrete): ?array;
}
