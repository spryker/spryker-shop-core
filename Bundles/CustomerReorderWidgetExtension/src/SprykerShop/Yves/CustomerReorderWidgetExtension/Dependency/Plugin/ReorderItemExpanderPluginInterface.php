<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * @deprecated Will be removed without replacement.
 *
 * Use this plugin to extend items before they added to the cart on the reorder action.
 */
interface ReorderItemExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands items with data from the order items.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expand(array $itemTransfers, OrderTransfer $orderTransfer): array;
}
