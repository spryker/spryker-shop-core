<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesConfigurableBundleWidget\Grouper;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;

class SalesOrderConfiguredBundleGrouper implements SalesOrderConfiguredBundleGrouperInterface
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\SalesConfigurableBundleWidget\Grouper\SalesOrderConfiguredBundleGrouper::getSalesOrderConfiguredBundlesByItems()} instead.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[]
     */
    public function getSalesOrderConfiguredBundles(OrderTransfer $orderTransfer, iterable $itemTransfers): array
    {
        $salesOrderConfiguredBundles = [];

        foreach ($orderTransfer->getSalesOrderConfiguredBundles() as $salesOrderConfiguredBundleTransfer) {
            if ($this->hasSalesOrderItems($salesOrderConfiguredBundleTransfer, $itemTransfers)) {
                $salesOrderConfiguredBundles[] = $salesOrderConfiguredBundleTransfer;
            }
        }

        return $salesOrderConfiguredBundles;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[]
     */
    public function getSalesOrderConfiguredBundlesByItems(array $itemTransfers): array
    {
        $salesOrderConfiguredBundleTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            if (
                !$itemTransfer->getSalesOrderConfiguredBundle() ||
                isset($salesOrderConfiguredBundleTransfers[$itemTransfer->getSalesOrderConfiguredBundle()->getIdSalesOrderConfiguredBundle()])
            ) {
                continue;
            }

            $salesOrderConfiguredBundleTransfer = clone $itemTransfer->getSalesOrderConfiguredBundle();
            $salesOrderConfiguredBundleItemTransfers = $this->getSalesOrderConfiguredBundleItems($itemTransfers, $salesOrderConfiguredBundleTransfer->getIdSalesOrderConfiguredBundle());
            $salesOrderConfiguredBundleTransfer->setSalesOrderConfiguredBundleItems(new ArrayObject($salesOrderConfiguredBundleItemTransfers));
            $salesOrderConfiguredBundleTransfers[$salesOrderConfiguredBundleTransfer->getIdSalesOrderConfiguredBundle()] =
                $salesOrderConfiguredBundleTransfer;
        }

        return $salesOrderConfiguredBundleTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param int $idSalesOrderConfiguredBundle
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer[]
     */
    protected function getSalesOrderConfiguredBundleItems(array $itemTransfers, int $idSalesOrderConfiguredBundle): array
    {
        $salesOrderConfiguredBundleItemTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getSalesOrderConfiguredBundle()->getIdSalesOrderConfiguredBundle() === $idSalesOrderConfiguredBundle) {
                $salesOrderConfiguredBundleItemTransfers[] = $itemTransfer->getSalesOrderConfiguredBundleItem();
            }
        }

        return $salesOrderConfiguredBundleItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    protected function hasSalesOrderItems(
        SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer,
        iterable $itemTransfers
    ): bool {
        $salesOrderItemIds = $this->getSalesOrderItemIdsFromConfiguredBundle($salesOrderConfiguredBundleTransfer);

        foreach ($itemTransfers as $itemTransfer) {
            if (in_array($itemTransfer->getIdSalesOrderItem(), $salesOrderItemIds)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     *
     * @return int[]
     */
    protected function getSalesOrderItemIdsFromConfiguredBundle(
        SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
    ): array {
        $salesOrderItemIds = [];

        foreach ($salesOrderConfiguredBundleTransfer->getSalesOrderConfiguredBundleItems() as $salesOrderConfiguredBundleItemTransfer) {
            $salesOrderItemIds[] = $salesOrderConfiguredBundleItemTransfer->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }
}
