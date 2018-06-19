<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistWidget\Dependency\Client;

use Generated\Shared\Transfer\WishlistCollectionTransfer;

class WishlistWidgetToWishlistClientBridge implements WishlistWidgetToWishlistClientInterface
{
    /**
     * @var \Spryker\Client\Wishlist\WishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @param \Spryker\Client\Wishlist\WishlistClientInterface $wishlistClient
     */
    public function __construct($wishlistClient)
    {
        $this->wishlistClient = $wishlistClient;
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistCollectionTransfer
     */
    public function getCustomerWishlistCollection(): WishlistCollectionTransfer
    {
        return $this->wishlistClient->getCustomerWishlistCollection();
    }
}
