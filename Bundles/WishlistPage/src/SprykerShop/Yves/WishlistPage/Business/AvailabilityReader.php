<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\WishlistPage\Business;

use SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToAvailabilityClientInterface;

class AvailabilityReader implements AvailabilityReaderInterface
{
    /**
     * @var \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToAvailabilityClientInterface
     */
    protected $availabilityClient;

    /**
     * @param \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToAvailabilityClientInterface $availabilityClient
     */
    public function __construct(WishlistPageToAvailabilityClientInterface $availabilityClient)
    {
        $this->availabilityClient = $availabilityClient;
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

            $storageAvailabilityTransfer = $this->availabilityClient->getProductAvailabilityByIdProductAbstract($idProductAbstract);
            $isAvailable = $storageAvailabilityTransfer->getConcreteProductAvailableItems()[$sku];

            $availability[$sku] = $isAvailable;
        }

        return $availability;
    }
}
