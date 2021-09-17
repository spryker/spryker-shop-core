<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

interface MerchantProductExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param array<mixed> $params
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemTransferWithMerchantReference(
        WishlistItemTransfer $wishlistItemTransfer,
        array $params,
        string $locale
    ): WishlistItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<mixed> $params
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithMerchantReference(
        ItemTransfer $itemTransfer,
        array $params,
        string $locale
    ): ItemTransfer;
}
