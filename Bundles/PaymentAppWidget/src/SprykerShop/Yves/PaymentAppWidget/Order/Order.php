<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerShop\Yves\PaymentAppWidget\Order;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToSalesClientInterface;

class Order implements OrderInterface
{
    /**
     * @param \SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToSalesClientInterface $salesClient
     */
    public function __construct(protected PaymentAppWidgetToSalesClientInterface $salesClient)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function cancelOrder(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$quoteTransfer->getOrderReference() || !$this->isForeignPayment($quoteTransfer)) {
            return $quoteTransfer;
        }

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setOrderReference($quoteTransfer->getOrderReference())
            ->setAllowCancellationWithoutCustomer(true);

        $orderCancelResponseTransfer = $this->salesClient->cancelOrder($orderCancelRequestTransfer);

        if (!$orderCancelResponseTransfer->getIsSuccessful()) {
            return $this->mapErrorsFromCancelResponseTransferToQuoteTransfer($orderCancelResponseTransfer, $quoteTransfer);
        }

        $quoteTransfer->setOrderReference(null);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCancelResponseTransfer $orderCancelResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapErrorsFromCancelResponseTransferToQuoteTransfer(
        OrderCancelResponseTransfer $orderCancelResponseTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        foreach ($orderCancelResponseTransfer->getMessages() as $messageTransfer) {
            $quoteTransfer->addError((new ErrorTransfer())->setMessage($messageTransfer->getMessage()));
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isForeignPayment(QuoteTransfer $quoteTransfer): bool
    {
        /** @var \Generated\Shared\Transfer\PaymentTransfer|null $paymentTransfer */
        $paymentTransfer = $quoteTransfer->getPayment();

        if (!$paymentTransfer || !$paymentTransfer->getPaymentSelection()) {
            return false;
        }

        return str_contains($paymentTransfer->getPaymentSelection(), PaymentTransfer::FOREIGN_PAYMENTS);
    }
}
