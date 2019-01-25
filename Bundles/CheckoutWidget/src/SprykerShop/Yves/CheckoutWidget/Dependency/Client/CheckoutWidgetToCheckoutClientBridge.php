<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutWidget\Dependency\Client;

use Generated\Shared\Transfer\CanProceedCheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CheckoutWidgetToCheckoutClientBridge implements CheckoutWidgetToCheckoutClientInterface
{
    /**
     * @var \Spryker\Client\Checkout\CheckoutClientInterface
     */
    protected $checkoutClient;

    /**
     * @param \Spryker\Client\Checkout\CheckoutClientInterface $checkoutClient
     */
    public function __construct($checkoutClient)
    {
        $this->checkoutClient = $checkoutClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CanProceedCheckoutResponseTransfer
     */
    public function isQuoteApplicableForCheckout(QuoteTransfer $quoteTransfer): CanProceedCheckoutResponseTransfer
    {
        return $this->checkoutClient->isQuoteApplicableForCheckout($quoteTransfer);
    }
}
