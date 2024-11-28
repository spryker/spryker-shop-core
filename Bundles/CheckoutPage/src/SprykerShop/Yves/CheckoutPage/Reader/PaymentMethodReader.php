<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Reader;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface;

class PaymentMethodReader implements PaymentMethodReaderInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface
     */
    protected CheckoutPageToPaymentClientInterface $paymentClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface
     */
    protected CheckoutPageToQuoteClientInterface $quoteClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig
     */
    protected CheckoutPageConfig $config;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface $paymentClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig $config
     */
    public function __construct(
        CheckoutPageToPaymentClientInterface $paymentClient,
        CheckoutPageToQuoteClientInterface $quoteClient,
        CheckoutPageConfig $config
    ) {
        $this->paymentClient = $paymentClient;
        $this->quoteClient = $quoteClient;
        $this->config = $config;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailablePaymentMethods(): PaymentMethodsTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();
        $paymentMethodsTransfer = $this->paymentClient->getAvailableMethods($quoteTransfer);

        return $this->filterExcludedPaymentMethods($paymentMethodsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function filterExcludedPaymentMethods(
        PaymentMethodsTransfer $paymentMethodsTransfer
    ): PaymentMethodsTransfer {
        $excludedPaymentMethodKeys = $this->config->getExcludedPaymentMethodKeys();
        $excludedPaymentMethodKeysMap = array_combine($excludedPaymentMethodKeys, $excludedPaymentMethodKeys);
        $filteredPaymentMethods = new PaymentMethodsTransfer();

        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethodTransfer) {
            if (!isset($excludedPaymentMethodKeysMap[$paymentMethodTransfer->getPaymentMethodKeyOrFail()])) {
                $filteredPaymentMethods->addMethod($paymentMethodTransfer);
            }
        }

        return $filteredPaymentMethods;
    }
}
