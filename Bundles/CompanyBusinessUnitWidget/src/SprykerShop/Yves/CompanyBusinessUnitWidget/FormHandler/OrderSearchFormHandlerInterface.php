<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyBusinessUnitWidget\FormHandler;

use Generated\Shared\Transfer\OrderListTransfer;

interface OrderSearchFormHandlerInterface
{
    /**
     * @param array $orderSearchFormData
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function handleOrderSearchFormSubmit(
        array $orderSearchFormData,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer;
}
