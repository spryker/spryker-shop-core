<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Model;

use Generated\Shared\Transfer\OrderTransfer;

interface CartFillerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function fillFromOrder(OrderTransfer $orderTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int[] $idOrderItems
     *
     * @return void
     */
    public function fillSelectedFromOrder(OrderTransfer $orderTransfer, array $idOrderItems): void;
}
