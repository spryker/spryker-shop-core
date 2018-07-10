<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper;

use Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

/**
 * @deprecated Use ProductMeasurementUnitStorageClientInterface instead.
 */
interface ProductMeasurementUnitMapperInterface
{
    /**
     * @deprecated Use ProductMeasurementUnitStorageClientInterface::findProductMeasurementUnit() instead.
     *
     * @see ProductMeasurementUnitStorageClientInterface::findProductMeasurementUnit()
     *
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer $measurementUnitStorageTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $measurementUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer
     */
    public function mapProductMeasurementUnit(
        ProductMeasurementUnitStorageTransfer $measurementUnitStorageTransfer,
        ProductMeasurementUnitTransfer $measurementUnitTransfer
    ): ProductMeasurementUnitTransfer;
}
