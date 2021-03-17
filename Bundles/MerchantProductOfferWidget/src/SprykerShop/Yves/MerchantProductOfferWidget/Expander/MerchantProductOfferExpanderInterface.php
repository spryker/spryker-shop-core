<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

interface MerchantProductOfferExpanderInterface
{
    /**
     * @phpstan-param array<mixed> $params
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemTransferWithProductOfferReference(
        WishlistItemTransfer $wishlistItemTransfer,
        array $params
    ): WishlistItemTransfer;

    /**
     * @phpstan-param array<mixed> $params
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithProductOfferReference(
        ItemTransfer $itemTransfer,
        array $params
    ): ItemTransfer;
}
