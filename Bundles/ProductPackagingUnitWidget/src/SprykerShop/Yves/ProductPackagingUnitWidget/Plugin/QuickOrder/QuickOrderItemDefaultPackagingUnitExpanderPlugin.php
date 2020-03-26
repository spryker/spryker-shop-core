<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Plugin\QuickOrder;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductPackagingUnitWidget\ProductPackagingUnitWidgetFactory getFactory()
 */
class QuickOrderItemDefaultPackagingUnitExpanderPlugin extends AbstractPlugin implements QuickOrderItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ItemTransfer with packaging unit data if available using product abstract ID and product concrete ID.
     * - Uses the default amount and default measurement unit settings.
     * - Returns ItemTransfer unchanged if no packaging unit data is available.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItem(ItemTransfer $itemTransfer): ItemTransfer
    {
        return $this->getFactory()
            ->getProductPackagingUnitStorageClient()
            ->expandItemTransferWithDefaultPackagingUnit($itemTransfer);
    }
}
