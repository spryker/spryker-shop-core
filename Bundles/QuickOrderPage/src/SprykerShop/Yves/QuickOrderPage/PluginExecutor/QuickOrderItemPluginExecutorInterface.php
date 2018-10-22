<?php

namespace SprykerShop\Yves\QuickOrderPage\PluginExecutor;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;

interface QuickOrderItemPluginExecutorInterface
{
    /**
     * @param QuickOrderTransfer $quickOrderTransfer
     * @param ProductConcreteTransfer[] $products
     *
     * @return QuickOrderTransfer
     */
    public function applyQuickOrderItemFilterPluginsOnQuickOrder(QuickOrderTransfer $quickOrderTransfer, array $products): QuickOrderTransfer;

    /**
     * @param QuickOrderItemTransfer $quickOrderItemTransfer
     * @param ProductConcreteTransfer|null $product
     *
     * @return QuickOrderItemTransfer
     */
    public function applyQuickOrderItemFilterPluginsOnQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer, ?ProductConcreteTransfer $product): QuickOrderItemTransfer;
}
