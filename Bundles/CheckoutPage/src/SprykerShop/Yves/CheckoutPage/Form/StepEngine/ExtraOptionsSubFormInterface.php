<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\StepEngine;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;

interface ExtraOptionsSubFormInterface extends SubFormInterface
{
    /**
     * @return array
     */
    public function getExtraOptions(): array;

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return $this
     */
    public function setPaymentMethodTransfer(PaymentMethodTransfer $paymentMethodTransfer);
}
