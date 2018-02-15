<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;

class QuoteWriter implements QuoteWriterInterface
{
    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface
     */
    private $cartClient;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface $cartClient
     */
    public function __construct(CustomerReorderWidgetToCartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @return void
     */
    public function fill(): void
    {
        $currentQuoteTransfer = $this->cartClient->getQuote();
        $newQuoteTransfer = new QuoteTransfer();

        if ($currentQuoteTransfer->getShippingAddress()) {
            $newQuoteTransfer->setShippingAddress($currentQuoteTransfer->getShippingAddress());
        }
        if ($currentQuoteTransfer->getBillingAddress()) {
            $newQuoteTransfer->setBillingAddress($currentQuoteTransfer->getBillingAddress());
        }
        if ($currentQuoteTransfer->getBillingSameAsShipping()) {
            $newQuoteTransfer->setBillingSameAsShipping($currentQuoteTransfer->getBillingSameAsShipping());
        }
        if ($currentQuoteTransfer->getShipment()) {
            $newQuoteTransfer->setShipment($currentQuoteTransfer->getShipment());
        }

        $this->cartClient->storeQuote($newQuoteTransfer);
    }
}
