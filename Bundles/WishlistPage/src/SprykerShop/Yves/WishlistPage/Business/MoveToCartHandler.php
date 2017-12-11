<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\WishlistPage\Business;

use Generated\Shared\Transfer\WishlistItemMetaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestTransfer;
use SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToCustomerClientInterface;
use SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToWishlistClientInterface;

class MoveToCartHandler implements MoveToCartHandlerInterface
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
     * @var \SprykerShop\Yves\WishlistPage\Business\AvailabilityReaderInterface
     */
    protected $availabilityReader;

    /**
     * @param \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToWishlistClientInterface $wishlistClient
     * @param \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\WishlistPage\Business\AvailabilityReaderInterface $availabilityReader
     */
    public function __construct(
        WishlistPageToWishlistClientInterface $wishlistClient,
        WishlistPageToCustomerClientInterface $customerClient,
        AvailabilityReaderInterface $availabilityReader
    ) {
        $this->wishlistClient = $wishlistClient;
        $this->customerClient = $customerClient;
        $this->availabilityReader = $availabilityReader;
    }

    /**
     * @param string $wishlistName
     * @param \Generated\Shared\Transfer\WishlistItemMetaTransfer[] $wishlistItemMetaTransferCollection
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    public function moveAllAvailableToCart($wishlistName, $wishlistItemMetaTransferCollection)
    {
        $wishlistMoveToCartRequestCollectionTransfer = $this->createMoveAvailableItemsToCartRequestCollection(
            $wishlistName,
            $wishlistItemMetaTransferCollection
        );

        if ($wishlistMoveToCartRequestCollectionTransfer->getRequests()->count() <= 0) {
            return $wishlistMoveToCartRequestCollectionTransfer;
        }

        return $this->wishlistClient->moveCollectionToCart($wishlistMoveToCartRequestCollectionTransfer);
    }

    /**
     * @param string $wishlistName
     * @param \Generated\Shared\Transfer\WishlistItemMetaTransfer[] $wishlistItemMetaTransferCollection
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    protected function createMoveAvailableItemsToCartRequestCollection($wishlistName, $wishlistItemMetaTransferCollection)
    {
        $wishlistMoveToCartRequestCollectionTransfer = new WishlistMoveToCartRequestCollectionTransfer();

        foreach ($wishlistItemMetaTransferCollection as $wishlistItemMetaTransfer) {
            $wishlistMoveToCartRequestTransfer = $this->createWishlistMoveToCartRequestTransfer(
                $wishlistName,
                $wishlistItemMetaTransfer
            );

            $wishlistMoveToCartRequestCollectionTransfer->addRequest($wishlistMoveToCartRequestTransfer);
        }

        return $wishlistMoveToCartRequestCollectionTransfer;
    }

    /**
     * @param string $wishlistName
     * @param \Generated\Shared\Transfer\WishlistItemMetaTransfer $wishlistItemMetaTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestTransfer
     */
    protected function createWishlistMoveToCartRequestTransfer($wishlistName, WishlistItemMetaTransfer $wishlistItemMetaTransfer)
    {
        $wishlistItemTransfer = new WishlistItemTransfer();
        $wishlistItemTransfer
            ->setSku($wishlistItemMetaTransfer->getSku())
            ->setWishlistName($wishlistName)
            ->setFkCustomer($this->getIdCustomer());

        $wishlistMoveToCartRequestTransfer = (new WishlistMoveToCartRequestTransfer())
            ->setSku($wishlistItemMetaTransfer->getSku())
            ->setWishlistItem($wishlistItemTransfer);

        return $wishlistMoveToCartRequestTransfer;
    }

    /**
     * @return int
     */
    protected function getIdCustomer()
    {
        return $this->customerClient
            ->getCustomer()
            ->getIdCustomer();
    }
}
