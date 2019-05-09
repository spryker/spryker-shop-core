<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget\Dependency\Client;

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
     * @return void
     */
    public function addCode(string $code)
    {
        $this->cartCodeClient->addCode($code);
    }

    /**
     * @return void
     */
    public function removeCode(string $code)
    {
        $this->cartCodeClient->removeCode($code);
    }

    /**
     * @return void
     */
    public function clearCodes()
    {
        $this->cartCodeClient->clearCodes();
    }
}
