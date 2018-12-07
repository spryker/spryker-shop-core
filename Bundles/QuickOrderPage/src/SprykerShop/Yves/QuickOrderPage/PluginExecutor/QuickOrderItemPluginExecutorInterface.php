<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\PluginExecutor;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;

interface QuickOrderItemPluginExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $products
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function applyQuickOrderItemFilterPluginsOnQuickOrder(QuickOrderTransfer $quickOrderTransfer, array $products): QuickOrderTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer|null $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function applyQuickOrderItemFilterPluginsOnQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer, ?ProductConcreteTransfer $productConcreteTransfer): QuickOrderItemTransfer;
}
