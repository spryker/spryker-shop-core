<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use Generated\Shared\Transfer\ItemTransfer;

interface AvailabilityCheckerInterface
{
    public function checkBySalesItem(ItemTransfer $itemTransfer): bool;
}
