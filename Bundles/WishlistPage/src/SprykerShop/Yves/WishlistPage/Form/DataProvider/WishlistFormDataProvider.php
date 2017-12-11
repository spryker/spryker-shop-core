<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
