<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Plugin\WishlistPage;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\WishlistPageExtension\Dependency\Plugin\WishlistItemRequestExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\MerchantProductWidget\MerchantProductWidgetFactory getFactory()
 */
class MerchantProductWishlistItemRequestExpanderPlugin extends AbstractPlugin implements WishlistItemRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `WishlistItem` by provided merchant_reference in params.
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
        /** @var string $locale */
        $locale = $this->getLocale();

        return $this->getFactory()
            ->createMerchantProductExpander()
            ->expandWishlistItemTransferWithMerchantReference($wishlistItemTransfer, $params, $locale);
    }
}
