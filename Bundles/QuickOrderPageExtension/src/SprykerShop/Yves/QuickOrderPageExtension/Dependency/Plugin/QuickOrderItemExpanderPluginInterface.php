<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;

interface QuickOrderItemExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands provided ItemTransfer with additional data.
     * - Executed before adding quick order items into cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItem(ItemTransfer $itemTransfer): ItemTransfer;
}
