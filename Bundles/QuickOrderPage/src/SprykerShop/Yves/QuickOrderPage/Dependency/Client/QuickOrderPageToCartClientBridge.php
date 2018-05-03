<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\Zed\CartStubInterface;

class QuickOrderPageToCartClientBridge implements QuickOrderPageToCartClientInterface
{
    /**
     * @var \Spryker\Client\Cart\CartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     */
    public function __construct($cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @return void
     */
    public function clearQuote(): void
    {
        $this->cartClient->clearQuote();
    }

    /**
     * @param array $itemTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItems(array $itemTransfers): QuoteTransfer
    {
        return $this->cartClient->addItems($itemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function storeQuote(QuoteTransfer $quoteTransfer): void
    {
        $this->cartClient->storeQuote($quoteTransfer);
    }

    /**
     * @return \Spryker\Client\Cart\Zed\CartStubInterface
     */
    public function getZedStub(): CartStubInterface
    {
        /** @var \Spryker\Client\Cart\CartClient $cartClient */
        $cartClient = $this->cartClient;

        return $cartClient->getZedStub();
    }
}
