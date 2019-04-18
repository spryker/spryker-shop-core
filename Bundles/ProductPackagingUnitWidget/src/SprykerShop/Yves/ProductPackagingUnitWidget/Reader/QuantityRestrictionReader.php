<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Reader;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;

class QuantityRestrictionReader implements QuantityRestrictionReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return float
     */
    public function getQuantityInterval(?ProductQuantityStorageTransfer $productQuantityStorageTransfer): float
    {
        if ($productQuantityStorageTransfer === null) {
            return 1;
        }

        return $productQuantityStorageTransfer->getQuantityInterval();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null $productConcreteAvailabilityTransfer
     *
     * @return float|null
     */
    public function getMaxQuantity(
        ?ProductQuantityStorageTransfer $productQuantityStorageTransfer,
        ?ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
    ): ?float {
        if ($productConcreteAvailabilityTransfer === null) {
            return 0;
        }

        if ($productQuantityStorageTransfer === null && $productConcreteAvailabilityTransfer->getIsNeverOutOfStock()) {
            return null;
        }

        $availability = $productConcreteAvailabilityTransfer->getAvailability();

        if (!$productConcreteAvailabilityTransfer->getIsNeverOutOfStock() && $productQuantityStorageTransfer === null) {
            return $availability;
        }

        return min($productQuantityStorageTransfer->getQuantityMax(), $availability);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return float
     */
    public function getMinQuantity(?ProductQuantityStorageTransfer $productQuantityStorageTransfer): float
    {
        if ($productQuantityStorageTransfer === null) {
            return 1;
        }

        return $productQuantityStorageTransfer->getQuantityMin();
    }
}
