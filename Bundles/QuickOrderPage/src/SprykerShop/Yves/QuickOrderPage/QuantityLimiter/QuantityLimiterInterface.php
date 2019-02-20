<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\QuantityLimiter;

use Generated\Shared\Transfer\QuickOrderTransfer;

interface QuantityLimiterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function limitQuickOrderItemsQuantity(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer;
}
