<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\QuantityNormalizer;

use Generated\Shared\Transfer\QuickOrderTransfer;

interface QuantityNormalizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function normalizeQuickOrderItemsQuantity(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer;
}
