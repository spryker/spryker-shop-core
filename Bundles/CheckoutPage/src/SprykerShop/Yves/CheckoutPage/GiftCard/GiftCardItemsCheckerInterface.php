<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\GiftCard;

use ArrayObject;

interface GiftCardItemsCheckerInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return bool
     */
    public function hasOnlyGiftCardItems(ArrayObject $itemTransfers): bool;
}
