<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Checker;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteChecker implements QuoteCheckerInterface
{
    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteLevelShipmentUsed(QuoteTransfer $quoteTransfer): bool
    {
        if (!$quoteTransfer->getShipment() || !$quoteTransfer->getShipment()->getMethod()) {
            return false;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->isItemWithShipmentAddress($itemTransfer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isItemWithShipmentAddress(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getShipment()
            && $itemTransfer->getShipment()->getShippingAddress();
    }
}
