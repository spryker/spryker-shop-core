<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentPage\Plugin\CustomerReorderWidget;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\ReorderItemSanitizerPluginInterface;

/**
 * @method \SprykerShop\Yves\ShipmentPage\ShipmentPageFactory getFactory()
 */
class ShipmentReorderItemSanitizerPlugin extends AbstractPlugin implements ReorderItemSanitizerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets the `ItemTransfer.shipment` property to `null` for each item.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function execute(array $itemTransfers): array
    {
        return $this->getFactory()
            ->createItemSanitizer()
            ->sanitizeShipment($itemTransfers);
    }
}
