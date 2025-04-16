<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerShop\Yves\PaymentAppWidget\Dependency\Client;

use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;

interface PaymentAppWidgetToSalesClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(OrderCancelRequestTransfer $orderCancelRequestTransfer): OrderCancelResponseTransfer;
}
