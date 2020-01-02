<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Expander;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

class ShipmentGroupExpander implements ShipmentGroupExpanderInterface
{
    /**
     * @uses \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_ITEMS
     */
    protected const BUNDLE_ITEMS = 'bundleItems';

    /**
     * @uses \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_PRODUCT
     */
    protected const BUNDLE_PRODUCT = 'bundleProduct';

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
            $shipmentGroupTransfer->setCartItems(
                $this->mapShipmentGroupCartItems($shipmentGroupTransfer, $mappedBundleItems)
            );
        }

        return $shipmentGroupTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $mappedBundleItems
     *
     * @return array
     */
    protected function mapShipmentGroupCartItems(ShipmentGroupTransfer $shipmentGroupTransfer, array $mappedBundleItems): array
    {
        $cartItems = [];

        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getRelatedBundleItemIdentifier()) {
                $cartItems[$itemTransfer->getGroupKey()] = [
                    static::BUNDLE_PRODUCT => $itemTransfer,
                    static::BUNDLE_ITEMS => [],
                ];

                continue;
            }

            $key = $this->generateKey($itemTransfer->getRelatedBundleItemIdentifier());

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

        return $cartItems;
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
                $bundleItems[$this->generateKey($productBundleGroupTransfer->getBundleItem()->getBundleItemIdentifier())] = $productBundleGroupTransfer->getBundleItem();
            }
        }

        return $bundleItems;
    }

    /**
     * @param string $bundleItemIdentifier
     *
     * @return string
     */
    protected function generateKey(string $bundleItemIdentifier): string
    {
        return static::BUNDLE_PREFIX_KEY . $bundleItemIdentifier;
    }
}
