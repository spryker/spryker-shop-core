<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Filter;

use Generated\Shared\Transfer\QuoteTransfer;

class ItemsFilter implements ItemsFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getItemsWithShipment(QuoteTransfer $quoteTransfer): array
    {
        if ($this->isQuoteLevelShipmentUsed($quoteTransfer)) {
            return $quoteTransfer->getItems()->getArrayCopy();
        }

        $itemTransfersWithShipment = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (
                $itemTransfer->getShipment()
                && $itemTransfer->getShipment()->getMethod()
                && $itemTransfer->getShipment()->getShippingAddress()
            ) {
                $itemTransfersWithShipment[] = $itemTransfer;
            }
        }

        return $itemTransfersWithShipment;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getItemsWithoutShipment(QuoteTransfer $quoteTransfer): array
    {
        if ($this->isQuoteLevelShipmentUsed($quoteTransfer)) {
            return [];
        }

        $itemTransfersWithoutShipment = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (
                !$itemTransfer->getShipment()
                || !$itemTransfer->getShipment()->getMethod()
                || !$itemTransfer->getShipment()->getShippingAddress()
            ) {
                $itemTransfersWithoutShipment[] = $itemTransfer;
            }
        }

        return $itemTransfersWithoutShipment;
    }

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
            if (
                $itemTransfer->getShipment()
                && $itemTransfer->getShipment()->getMethod()
                && $itemTransfer->getShipment()->getShippingAddress()
            ) {
                return false;
            }
        }

        return true;
    }
}
