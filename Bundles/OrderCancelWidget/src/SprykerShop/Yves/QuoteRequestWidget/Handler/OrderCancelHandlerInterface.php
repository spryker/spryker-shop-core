<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCancelWidget\Handler;

use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Symfony\Component\HttpFoundation\Request;

interface OrderCancelHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(Request $request): OrderCancelResponseTransfer;
}
