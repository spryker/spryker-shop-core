<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Plugin\CustomerReorderWidget;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\ReorderItemSanitizerPluginInterface;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
class RemunerationAmountReorderItemSanitizerPlugin extends AbstractPlugin implements ReorderItemSanitizerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets the `ItemTransfer.remunerationAmount` property to `null` for each item.
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
            ->sanitizeRemunerationAmount($itemTransfers);
    }
}
