<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteExpander implements QuoteExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteItemsWithShipmentType(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->isItemExpandable($itemTransfer)) {
                continue;
            }
            $itemTransfer->getShipmentOrFail()->setShipmentTypeUuid($itemTransfer->getShipmentTypeOrFail()->getUuidOrFail());
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isItemExpandable(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getShipment() !== null
            && $itemTransfer->getShipmentType() !== null
            && $itemTransfer->getShipmentTypeOrFail()->getUuid() !== null;
    }
}
