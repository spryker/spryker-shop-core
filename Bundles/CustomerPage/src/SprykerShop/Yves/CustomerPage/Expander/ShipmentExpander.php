<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Expander;

use Generated\Shared\Transfer\QuoteTransfer;

class ShipmentExpander implements ShipmentExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandShipmentForBundleItems(QuoteTransfer $quoteTransfer, array $bundleItems): QuoteTransfer
    {
        $quoteTransfer = $this->setShipmentForBundleItems($quoteTransfer, $bundleItems);
        $quoteTransfer = $this->setShipmentForItemsInBundle($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setShipmentForBundleItems(QuoteTransfer $quoteTransfer, array $bundleItems): QuoteTransfer
    {
        $indexedBundleItemShipments = $this->indexBundleItemShipmentsByGroupKey($bundleItems);

        foreach ($quoteTransfer->getBundleItems() as $bundleItem) {
            $shipmentTransfer = $indexedBundleItemShipments[$bundleItem->getGroupKey()] ?? null;

            if ($shipmentTransfer) {
                $bundleItem->setShipment($shipmentTransfer);
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setShipmentForItemsInBundle(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $indexedBundleItemShipments = $this->indexBundleItemShipmentsByBundleItemIdentifier(
            $quoteTransfer->getBundleItems()->getArrayCopy()
        );

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentTransfer = $indexedBundleItemShipments[$itemTransfer->getRelatedBundleItemIdentifier()] ?? null;

            if ($itemTransfer->getRelatedBundleItemIdentifier() && $shipmentTransfer) {
                $itemTransfer->setShipment($shipmentTransfer);
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer[]
     */
    protected function indexBundleItemShipmentsByGroupKey(array $bundleItems): array
    {
        $indexedBundleItemShipments = [];

        foreach ($bundleItems as $bundleItem) {
            $indexedBundleItemShipments[$bundleItem->getGroupKey()] = $bundleItem->getShipment();
        }

        return $indexedBundleItemShipments;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer[]
     */
    protected function indexBundleItemShipmentsByBundleItemIdentifier(array $bundleItems): array
    {
        $indexedBundleItemShipments = [];

        foreach ($bundleItems as $bundleItem) {
            $indexedBundleItemShipments[$bundleItem->getBundleItemIdentifier()] = $bundleItem->getShipment();
        }

        return $indexedBundleItemShipments;
    }
}
