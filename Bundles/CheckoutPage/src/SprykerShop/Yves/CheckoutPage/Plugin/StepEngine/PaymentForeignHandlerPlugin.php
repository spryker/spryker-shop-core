<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Plugin\StepEngine;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageFactory getFactory()
 */
class PaymentForeignHandlerPlugin extends AbstractPlugin implements StepHandlerPluginInterface
{
    /**
     * @uses \SprykerShop\Yves\PaymentPage\Form\PaymentForeignSubForm::FIELD_PAYMENT_METHOD_NAME
     *
     * @var string
     */
    protected const FIELD_PAYMENT_METHOD_NAME = 'paymentMethodName';

    /**
     * @uses \SprykerShop\Yves\PaymentPage\Form\PaymentForeignSubForm::FIELD_PAYMENT_PROVIDER_NAME
     *
     * @var string
     */
    protected const FIELD_PAYMENT_PROVIDER_NAME = 'paymentProviderName';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToDataClass(Request $request, AbstractTransfer $quoteTransfer)
    {
        $paymentMethodKey = $this->getFactory()->createPaymentMethodKeyExtractor()->getPaymentMethodKey($quoteTransfer->getPayment());
        $paymentMethodFormData = $quoteTransfer->getPayment()->getForeignPayments()[$paymentMethodKey];

        $quoteTransfer->getPayment()
            ->setPaymentProvider($paymentMethodFormData[static::FIELD_PAYMENT_PROVIDER_NAME])
            ->setPaymentMethod($paymentMethodFormData[static::FIELD_PAYMENT_METHOD_NAME]);

        return $quoteTransfer;
    }
}
