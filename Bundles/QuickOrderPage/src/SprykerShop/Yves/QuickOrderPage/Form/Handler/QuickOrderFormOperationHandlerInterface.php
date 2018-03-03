<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Handler;

use Generated\Shared\Transfer\QuickOrderTransfer;

interface QuickOrderFormOperationHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrder
     *
     * @return void
     */
    public function addToCart(QuickOrderTransfer $quickOrder): void;

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrder
     *
     * @return void
     */
    public function createOrder(QuickOrderTransfer $quickOrder): void;
}
