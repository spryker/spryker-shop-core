<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Updater;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutConfigurationTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToQuoteClientInterface;
use SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig;

class QuoteUpdater implements QuoteUpdaterInterface
{
    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToQuoteClientInterface
     */
    protected PaymentAppWidgetToQuoteClientInterface $quoteClient;

    /**
     * @param \SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToQuoteClientInterface $quoteClient
     */
    public function __construct(PaymentAppWidgetToQuoteClientInterface $quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer
     *
     * @return void
     */
    public function updateQuoteWithPaymentData(
        QuoteTransfer $quoteTransfer,
        PaymentTransfer $paymentTransfer,
        PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer
    ): void {
        $paymentTransfer = $this->expandPaymentWithCheckoutConfiguration($paymentTransfer);
        $quoteTransfer = $this->expandQuoteWithPaymentData($quoteTransfer, $paymentTransfer, $preOrderPaymentResponseTransfer);

        $this->quoteClient->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function expandQuoteWithPaymentData(
        QuoteTransfer $quoteTransfer,
        PaymentTransfer $paymentTransfer,
        PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer
    ): QuoteTransfer {
        return $quoteTransfer->setPreOrderPaymentData($preOrderPaymentResponseTransfer->getPreOrderPaymentData())
            ->setPayments(new ArrayObject([$paymentTransfer]))
            ->setPayment($paymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function expandPaymentWithCheckoutConfiguration(
        PaymentTransfer $paymentTransfer
    ): PaymentTransfer {
        return $paymentTransfer->setCheckoutConfiguration((new CheckoutConfigurationTransfer())
            ->setStrategy(PaymentAppWidgetConfig::CHECKOUT_CONFIGURATION_STRATEGY_EXPRESS_CHECKOUT));
    }
}
