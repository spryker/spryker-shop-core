<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentPage\Plugin\StepEngine;

use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerShop\Yves\CheckoutPage\Form\StepEngine\ExtraOptionsSubFormInterface;
use SprykerShop\Yves\PaymentPage\Exception\InvalidPaymentMethodException;

/**
 * @method \SprykerShop\Yves\PaymentPage\PaymentPageFactory getFactory()
 */
class PaymentForeignSubFormPlugin extends AbstractPaymentForeignSubFormPlugin
{
    /**
     * @throws \SprykerShop\Yves\PaymentPage\Exception\InvalidPaymentMethodException
     *
     * @return \SprykerShop\Yves\CheckoutPage\Form\StepEngine\ExtraOptionsSubFormInterface
     */
    public function createSubForm(): ExtraOptionsSubFormInterface
    {
        if ($this->paymentMethodTransfer === null) {
            throw new InvalidPaymentMethodException('PaymentMethod should be set first.');
        }

        return $this->getFactory()->createPaymentForeignSubForm()
            ->setPaymentMethodTransfer($this->paymentMethodTransfer);
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return $this->getFactory()->createPaymentForeignFormDataProvider();
    }
}
