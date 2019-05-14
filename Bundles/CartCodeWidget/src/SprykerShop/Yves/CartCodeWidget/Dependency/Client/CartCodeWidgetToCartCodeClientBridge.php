<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\CartCodeOperationResultTransfer;

class CartCodeWidgetToCartCodeClientBridge implements CartCodeWidgetToCartCodeClientInterface
{
    /**
     * @var \Spryker\Client\CartCode\CartCodeClientInterface
     */
    protected $cartCodeClient;

    /**
     * @param \Spryker\Client\CartCode\CartCodeClientInterface $cartCodeClient
     */
    public function __construct($cartCodeClient)
    {
        $this->cartCodeClient = $cartCodeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function addCode(QuoteTransfer $quoteTransfer, string $code): CartCodeOperationResultTransfer
    {
        return $this->cartCodeClient->addCode($quoteTransfer, $code);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function removeCode(QuoteTransfer $quoteTransfer, string $code): CartCodeOperationResultTransfer
    {
        return $this->cartCodeClient->removeCode($quoteTransfer, $code);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function clearCodes(QuoteTransfer $quoteTransfer): CartCodeOperationResultTransfer
    {
        return $this->cartCodeClient->clearAllCodes($quoteTransfer);
    }
}
