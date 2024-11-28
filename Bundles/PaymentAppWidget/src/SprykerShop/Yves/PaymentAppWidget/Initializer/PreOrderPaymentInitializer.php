<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Initializer;

use ArrayObject;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToPaymentClientInterface;

class PreOrderPaymentInitializer implements PreOrderPaymentInitializerInterface
{
    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToPaymentClientInterface
     */
    protected PaymentAppWidgetToPaymentClientInterface $paymentClient;

    /**
     * @param \SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToPaymentClientInterface $paymentClient
     */
    public function __construct(PaymentAppWidgetToPaymentClientInterface $paymentClient)
    {
        $this->paymentClient = $paymentClient;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function initializePreOrderPayment(
        PaymentTransfer $paymentTransfer,
        QuoteTransfer $quoteTransfer
    ): PreOrderPaymentResponseTransfer {
        $quoteTransfer = $this->expandQuoteWithPayment($quoteTransfer, $paymentTransfer);
        $preOrderPaymentRequestTransfer = $this->createPreOrderPaymentRequestTransfer($paymentTransfer, $quoteTransfer);

        return $this->paymentClient->initializePreOrderPayment($preOrderPaymentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer
     */
    protected function createPreOrderPaymentRequestTransfer(
        PaymentTransfer $paymentTransfer,
        QuoteTransfer $quoteTransfer
    ): PreOrderPaymentRequestTransfer {
        return (new PreOrderPaymentRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setPayment($paymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function expandQuoteWithPayment(
        QuoteTransfer $quoteTransfer,
        PaymentTransfer $paymentTransfer
    ): QuoteTransfer {
        return (new QuoteTransfer())
            ->fromArray($quoteTransfer->toArray(), true)
            ->setPayment($paymentTransfer)
            ->setPayments(new ArrayObject([$paymentTransfer]))
            ->setItems(new ArrayObject());
    }
}
