<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Handler;

use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface CheckoutErrorMessageHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer[]
     */
    public function getUniqueCheckoutErrorMessages(CheckoutResponseTransfer $checkoutResponseTransfer): array;
}
