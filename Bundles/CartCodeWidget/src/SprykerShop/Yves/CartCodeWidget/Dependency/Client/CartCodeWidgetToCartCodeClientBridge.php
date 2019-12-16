<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget\Dependency\Client;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;

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
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function addCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        return $this->cartCodeClient->addCartCode($cartCodeRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function removeCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        return $this->cartCodeClient->removeCartCode($cartCodeRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function clearCartCodes(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        return $this->cartCodeClient->clearCartCodes($cartCodeRequestTransfer);
    }
}
