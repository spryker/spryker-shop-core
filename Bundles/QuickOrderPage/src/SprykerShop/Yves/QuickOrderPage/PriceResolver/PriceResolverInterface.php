<?php

namespace SprykerShop\Yves\QuickOrderPage\PriceResolver;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;

interface PriceResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function setSumPriceForQuickOrderTransfer(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return QuickOrderItemTransfer
     */
    public function setSumPriceForQuickOrderItemTransfer(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderItemTransfer;
}
