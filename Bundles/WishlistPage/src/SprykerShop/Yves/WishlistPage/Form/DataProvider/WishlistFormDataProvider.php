<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Form\DataProvider;

use Generated\Shared\Transfer\WishlistTransfer;
use SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToCustomerClientInterface;
use SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToWishlistClientInterface;

class WishlistFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToWishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @var \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToWishlistClientInterface $wishlistClient
     * @param \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToCustomerClientInterface $customerClient
     */
    public function __construct(WishlistPageToWishlistClientInterface $wishlistClient, WishlistPageToCustomerClientInterface $customerClient)
    {
        $this->wishlistClient = $wishlistClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @param string $wishlistName
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getData($wishlistName)
    {
        $customerTransfer = $this->customerClient->getCustomer();

        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer
            ->setName($wishlistName)
            ->setFkCustomer($customerTransfer->getIdCustomer());

        return $this->wishlistClient->getWishlist($wishlistTransfer);
    }
}
