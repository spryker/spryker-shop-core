<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;

class ItemStateMapper implements ItemStateMapperInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return array
     */
    public function aggregateItemStatesDisplayNamesByOrderReference(ArrayObject $orderTransfers): array
    {
        $aggregatedItemStateDisplayNames = [];
        foreach ($orderTransfers as $orderTransfer) {
            $aggregatedItemStateDisplayNames[$orderTransfer->getOrderReference()] = $this->aggregateItemStatesDisplayNamesByOrder($orderTransfer);
        }

        return $aggregatedItemStateDisplayNames;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string[]
     */
    protected function aggregateItemStatesDisplayNamesByOrder(OrderTransfer $orderTransfer): array
    {
        $displayNames = [];
        foreach ($orderTransfer->getAggregatedItemStates() as $aggregatedItemStateTransfer) {
            if (!$aggregatedItemStateTransfer->getDisplayName()) {
                continue;
            }

            $displayNames[$aggregatedItemStateTransfer->getDisplayName()] = $aggregatedItemStateTransfer->getDisplayName();
        }

        return array_values($displayNames);
    }
}
