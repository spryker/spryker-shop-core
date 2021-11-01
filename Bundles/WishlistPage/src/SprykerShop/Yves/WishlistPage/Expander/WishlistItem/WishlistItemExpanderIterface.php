<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Expander\WishlistItem;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

interface WishlistItemExpanderIterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param array<mixed> $requestParams
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemTransferWithRequestedParams(
        WishlistItemTransfer $wishlistItemTransfer,
        array $requestParams
    ): WishlistItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array<mixed> $productConcreteStorageData
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransferWithProductConcreteData(
        ProductViewTransfer $productViewTransfer,
        array $productConcreteStorageData,
        string $locale
    ): ProductViewTransfer;
}
