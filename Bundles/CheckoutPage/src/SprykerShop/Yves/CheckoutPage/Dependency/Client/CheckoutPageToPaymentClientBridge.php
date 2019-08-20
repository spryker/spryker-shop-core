<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class CheckoutPageToPaymentClientBridge implements CheckoutPageToPaymentClientInterface
{
    /**
     * @var \Spryker\Client\Payment\PaymentClientInterface
     */
    protected $paymentClient;

    /**
     * @param \Spryker\Client\Payment\PaymentClientInterface $paymentClient
     */
    public function __construct($paymentClient)
    {
        $this->paymentClient = $paymentClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        return $this->paymentClient->getAvailableMethods($quoteTransfer);
    }
}
