<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Model;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

/**
 * @deprecated Use ProductMeasurementUnitStorageClientInterface instead.
 *
 * @see ProductMeasurementUnitStorageClientInterface
 */
interface ProductMeasurementBaseUnitReaderInterface
{
    /**
     * @deprecated Use ProductMeasurementUnitStorageClientInterface::findProductMeasurementBaseUnitByIdProduct() instead.
     *
     * @see ProductMeasurementUnitStorageClientInterface::findProductMeasurementBaseUnitByIdProduct()
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null
     */
    public function findProductMeasurementBaseUnitByIdProductConcrete(int $idProductConcrete): ?ProductMeasurementUnitTransfer;
}
