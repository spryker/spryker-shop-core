<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Form\DataProvider;

use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use SprykerShop\Yves\WishlistPage\Form\AddAllAvailableProductsToCartFormType;

class AddAllAvailableProductsToCartFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer|null $wishlistOverviewResponseTransfer
     *
     * @return array
     */
    public function getData(WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer = null)
    {
        $data = [
            AddAllAvailableProductsToCartFormType::WISHLIST_ITEM_META_COLLECTION => $this->getWishlistItemMetaCollection($wishlistOverviewResponseTransfer),
        ];

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer|null $wishlistOverviewResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemMetaTransfer[]
     */
    protected function getWishlistItemMetaCollection(WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer = null)
    {
        if (!$wishlistOverviewResponseTransfer) {
            return [];
        }

        return $wishlistOverviewResponseTransfer->getMeta()
            ->getWishlistItemMetaCollection()
            ->getArrayCopy();
    }
}
