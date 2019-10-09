<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesConfigurableBundleWidget\Grouper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;

class SalesOrderConfiguredBundleGrouper implements SalesOrderConfiguredBundleGrouperInterface
{
    /**
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
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    protected function hasSalesOrderItems(
        SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer,
        iterable $itemTransfers
    ): bool {
        foreach ($itemTransfers as $itemTransfer) {
            if (in_array($itemTransfer->getIdSalesOrderItem(), $this->getSalesOrderItemIdsFromConfiguredBundle($salesOrderConfiguredBundleTransfer))) {
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
