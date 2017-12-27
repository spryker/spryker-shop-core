<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\WishlistPage\Business;

use SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToAvailabilityStorageClientInterface;

class AvailabilityReader implements AvailabilityReaderInterface
{
    /**
     * @var \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToAvailabilityStorageClientInterface
     */
    protected $availabilityStorageClient;

    /**
     * @param \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToAvailabilityStorageClientInterface $availabilityStorageClient
     */
    public function __construct(WishlistPageToAvailabilityStorageClientInterface $availabilityStorageClient)
    {
        $this->availabilityStorageClient = $availabilityStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemMetaTransfer[] $wishlistItemMetaTransferCollection
     *
     * @return array
     */
    public function getAvailability($wishlistItemMetaTransferCollection)
    {
        $availability = [];

        foreach ($wishlistItemMetaTransferCollection as $wishlistItemMetaTransfer) {
            $sku = $wishlistItemMetaTransfer->getSku();
            $idProductAbstract = $wishlistItemMetaTransfer->getIdProductAbstract();

            $storageAvailabilityTransfer = $this->availabilityStorageClient->getProductAvailabilityByIdProductAbstract($idProductAbstract);
            $isAvailable = $storageAvailabilityTransfer->getConcreteProductAvailableItems()[$sku];

            $availability[$sku] = $isAvailable;
        }

        return $availability;
    }
}
