<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Checker;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

class QuoteChecker implements QuoteCheckerInterface
{
    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteLevelShipmentUsed(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        $quoteTransfer = $quoteRequestTransfer->getLatestVersion()->getQuote();

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
