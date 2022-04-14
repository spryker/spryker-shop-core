<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentPage\Plugin\StepEngine;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;

abstract class AbstractPaymentForeignSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * @var \Generated\Shared\Transfer\PaymentMethodTransfer|null
     */
    protected $paymentMethodTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return $this
     */
    public function setPaymentMethodTransfer(PaymentMethodTransfer $paymentMethodTransfer)
    {
        $this->paymentMethodTransfer = $paymentMethodTransfer;

        return $this;
    }
}
