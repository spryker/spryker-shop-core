<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartReorderPage\Dependency\Client;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderResponseTransfer;

class CartReorderPageToCartReorderClientBridge implements CartReorderPageToCartReorderClientInterface
{
    /**
     * @var \Spryker\Client\CartReorder\CartReorderClientInterface
     */
    protected $cartReorderClient;

    /**
     * @param \Spryker\Client\CartReorder\CartReorderClientInterface $cartReorderClient
     */
    public function __construct($cartReorderClient)
    {
        $this->cartReorderClient = $cartReorderClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function reorder(CartReorderRequestTransfer $cartReorderRequestTransfer): CartReorderResponseTransfer
    {
        return $this->cartReorderClient->reorder($cartReorderRequestTransfer);
    }
}
