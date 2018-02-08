<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface OrderRepositoryInterface
{
    /**
     * @param int $idSalesOrder
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function getOrderTransfer(int $idSalesOrder, CustomerTransfer $customerTransfer): ?OrderTransfer;
}
