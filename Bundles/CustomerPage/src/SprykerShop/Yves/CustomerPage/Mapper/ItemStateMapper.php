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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return array<string[][]>
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
     * @return array<string[]>
     */
    protected function aggregateItemStatesDisplayNamesByOrder(OrderTransfer $orderTransfer): array
    {
        $displayNames = [];
        foreach ($orderTransfer->getAggregatedItemStates() as $aggregatedItemStateTransfer) {
            $displayName = $aggregatedItemStateTransfer->getDisplayName();
            if (!$displayName) {
                continue;
            }

            $displayNames[$displayName][] = $aggregatedItemStateTransfer->getName();
        }

        return $displayNames;
    }
}
