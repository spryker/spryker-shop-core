<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Reader;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface;

class PaymentMethodReader implements PaymentMethodReaderInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface
     */
    protected $paymentClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface $paymentClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface $quoteClient
     */
    public function __construct(
        CheckoutPageToPaymentClientInterface $paymentClient,
        CheckoutPageToQuoteClientInterface $quoteClient
    ) {
        $this->paymentClient = $paymentClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailablePaymentMethods(): PaymentMethodsTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        return $this->paymentClient->getAvailableMethods($quoteTransfer);
    }
}
