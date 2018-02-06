<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use Generated\Shared\Transfer\CustomerTransfer;

interface ReorderHandlerInterface
{
    /**
     * @param int $idSalesOrder
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function reorder(int $idSalesOrder, CustomerTransfer $customerTransfer): void;

    /**
     * @param int $idSalesOrder
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param int[] $idOrderItems
     *
     * @return void
     */
    public function reorderItems(int $idSalesOrder, CustomerTransfer $customerTransfer, array $idOrderItems): void;
}
