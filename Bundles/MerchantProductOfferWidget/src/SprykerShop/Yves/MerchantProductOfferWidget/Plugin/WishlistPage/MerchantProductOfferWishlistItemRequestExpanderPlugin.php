<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Plugin\WishlistPage;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\WishlistPageExtension\Dependency\Plugin\WishlistItemRequestExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class MerchantProductOfferWishlistItemRequestExpanderPlugin extends AbstractPlugin implements WishlistItemRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `WishlistItem` by provided `product_offer_reference` in params.
     * - Sets `merchant_reference` related to `product_offer_reference`.
     *
     * @api
     *
     * @phpstan-param array<string, mixed> $params
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expand(WishlistItemTransfer $wishlistItemTransfer, array $params): WishlistItemTransfer
    {
        return $this->getFactory()
            ->createMerchantProductOfferExpander()
            ->expandWishlistItemTransferWithProductOfferReference($wishlistItemTransfer, $params);
    }
}
