<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\WishlistPage\Business;

use Spryker\Client\AvailabilityStorage\AvailabilityStorageClientInterface;

class AvailabilityReader implements AvailabilityReaderInterface
{
    /**
     * @var \Spryker\Client\AvailabilityStorage\AvailabilityStorageClientInterface
     */
    protected $availabilityStorageClient;

    /**
     * @param \Spryker\Client\AvailabilityStorage\AvailabilityStorageClientInterface $availabilityStorageClient
     */
    public function __construct(AvailabilityStorageClientInterface $availabilityStorageClient)
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
