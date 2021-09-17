<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesProductConfigurationWidget\Plugin\CustomerReorderWidget;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\ReorderItemExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\SalesProductConfigurationWidget\SalesProductConfigurationWidgetFactory getFactory()
 */
class ProductConfigurationReorderItemExpanderPlugin extends AbstractPlugin implements ReorderItemExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands items with product configuration based on data from order items.
     * - Requires `Item::groupKey` and `Order::items::groupKey` to be set.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expand(array $itemTransfers, OrderTransfer $orderTransfer): array
    {
        return $this->getFactory()
            ->getSalesProductConfigurationClient()
            ->expandItemsWithProductConfiguration($itemTransfers, $orderTransfer);
    }
}
