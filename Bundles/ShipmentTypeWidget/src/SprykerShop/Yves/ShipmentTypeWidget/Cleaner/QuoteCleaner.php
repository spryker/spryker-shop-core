<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Cleaner;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteCleaner implements QuoteCleanerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function cleanShipmentTypeUuidFromQuoteItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getShipment()) {
                continue;
            }
            $itemTransfer->getShipmentOrFail()->setShipmentTypeUuid(null);

            $this->cleanShipmentTypeFromItemShipmentMethod($itemTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function cleanShipmentTypeFromItemShipmentMethod(ItemTransfer $itemTransfer): void
    {
        if (!$itemTransfer->getShipmentOrFail()->getMethod() || !$itemTransfer->getShipmentOrFail()->getMethodOrFail()->getShipmentType()) {
            return;
        }

        $itemTransfer->getShipmentOrFail()->getMethodOrFail()->setShipmentType(null);
    }
}
