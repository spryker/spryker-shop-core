<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Reader;

use Generated\Shared\Transfer\PaymentMethodsTransfer;

interface PaymentMethodReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailablePaymentMethods(): PaymentMethodsTransfer;
}
