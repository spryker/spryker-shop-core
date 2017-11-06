<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\WishlistPage\Business;

interface AvailabilityReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistItemMetaTransfer[] $wishlistItemMetaTransferCollection
     *
     * @return array
     */
    public function getAvailability($wishlistItemMetaTransferCollection);
}
