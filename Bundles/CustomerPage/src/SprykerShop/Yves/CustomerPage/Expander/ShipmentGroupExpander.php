<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Expander;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;

class ShipmentGroupExpander implements ShipmentGroupExpanderInterface
{
    public const BUNDLE_ITEMS = 'bundleItems';
    public const BUNDLE_PRODUCT = 'bundleProduct';
    protected const BUNDLE_PREFIX_KEY = 'bundle_prefix_';

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function expandShipmentGroupsWithCartItems(ArrayObject $shipmentGroupTransfers, OrderTransfer $orderTransfer): ArrayObject
    {
        $mappedBundleItems = $this->getMappedBundleItems($orderTransfer);

        foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
            $cartItems = [];

            foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
                if (!$itemTransfer->getRelatedBundleItemIdentifier()) {
                    $cartItems[$itemTransfer->getGroupKey()] = [
                        static::BUNDLE_PRODUCT => $itemTransfer,
                        static::BUNDLE_ITEMS => [],
                    ];

                    continue;
                }

                $key = static::BUNDLE_PREFIX_KEY . $itemTransfer->getRelatedBundleItemIdentifier();

                if (!isset($cartItems[$key])) {
                    $cartItems[$key] = [
                        static::BUNDLE_PRODUCT => null,
                        static::BUNDLE_ITEMS => [],
                    ];
                }

                $cartItems[$key][static::BUNDLE_ITEMS][] = $itemTransfer;

                if (!$cartItems[$key][static::BUNDLE_PRODUCT] && isset($mappedBundleItems[$key])) {
                    $cartItems[$key][static::BUNDLE_PRODUCT] = $mappedBundleItems[$key];
                }
            }

            $shipmentGroupTransfer->setCartItems($cartItems);
        }

        return $shipmentGroupTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getMappedBundleItems(OrderTransfer $orderTransfer): array
    {
        $bundleItems = [];

        foreach ($orderTransfer->getItemGroups() as $productBundleGroupTransfer) {
            if ($productBundleGroupTransfer->getIsBundle()) {
                $bundleItems[static::BUNDLE_PREFIX_KEY . $productBundleGroupTransfer->getBundleItem()->getBundleItemIdentifier()] = $productBundleGroupTransfer->getBundleItem();
            }
        }

        return $bundleItems;
    }
}
