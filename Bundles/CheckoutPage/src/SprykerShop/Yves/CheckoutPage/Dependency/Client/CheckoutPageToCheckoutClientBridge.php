<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Dependency\Client;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TranslatedCheckoutErrorMessagesTransfer;

class CheckoutPageToCheckoutClientBridge implements CheckoutPageToCheckoutClientInterface
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
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer)
    {
        return $this->checkoutClient->placeOrder($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\TranslatedCheckoutErrorMessagesTransfer
     */
    public function translateCheckoutErrorMessages(CheckoutResponseTransfer $checkoutResponseTransfer): TranslatedCheckoutErrorMessagesTransfer
    {
        return $this->checkoutClient->translateCheckoutErrorMessages($checkoutResponseTransfer);
    }
}
