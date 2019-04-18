<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Reader;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;

interface QuantityRestrictionReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return float
     */
    public function getQuantityInterval(?ProductQuantityStorageTransfer $productQuantityStorageTransfer): float;

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null $productConcreteAvailabilityTransfer
     *
     * @return float|null
     */
    public function getMaxQuantity(?ProductQuantityStorageTransfer $productQuantityStorageTransfer, ?ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer): ?float;

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return float
     */
    public function getMinQuantity(?ProductQuantityStorageTransfer $productQuantityStorageTransfer): float;
}
