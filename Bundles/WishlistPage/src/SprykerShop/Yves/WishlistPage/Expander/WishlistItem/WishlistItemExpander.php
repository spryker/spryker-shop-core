<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Expander\WishlistItem;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

class WishlistItemExpander implements WishlistItemExpanderIterface
{
    /**
     * @var array<\SprykerShop\Yves\WishlistPageExtension\Dependency\Plugin\WishlistItemRequestExpanderPluginInterface>
     */
    protected $wishlistItemRequestExpanderPlugins;

    /**
     * @var array<\Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface>
     */
    protected $wishlistItemExpanderPlugins;

    /**
     * @param array<\SprykerShop\Yves\WishlistPageExtension\Dependency\Plugin\WishlistItemRequestExpanderPluginInterface> $wishlistItemRequestExpanderPlugins
     * @param array<\Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface> $wishlistItemExpanderPlugins
     */
    public function __construct(
        array $wishlistItemRequestExpanderPlugins,
        array $wishlistItemExpanderPlugins
    ) {
        $this->wishlistItemRequestExpanderPlugins = $wishlistItemRequestExpanderPlugins;
        $this->wishlistItemExpanderPlugins = $wishlistItemExpanderPlugins;
    }

    /**
     * @phpstan-param array<mixed> $requestParams
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param array $requestParams
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemTransferWithRequestedParams(
        WishlistItemTransfer $wishlistItemTransfer,
        array $requestParams
    ): WishlistItemTransfer {
        foreach ($this->wishlistItemRequestExpanderPlugins as $wishlistItemRequestExpanderPlugin) {
            $wishlistItemTransfer = $wishlistItemRequestExpanderPlugin->expand($wishlistItemTransfer, $requestParams);
        }

        return $wishlistItemTransfer;
    }

    /**
     * @phpstan-param array<mixed> $productConcreteStorageData
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productConcreteStorageData
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransferWithProductConcreteData(
        ProductViewTransfer $productViewTransfer,
        array $productConcreteStorageData,
        string $locale
    ): ProductViewTransfer {
        foreach ($this->wishlistItemExpanderPlugins as $productViewExpanderPlugin) {
            $productViewTransfer = $productViewExpanderPlugin->expandProductViewTransfer(
                $productViewTransfer,
                $productConcreteStorageData,
                $locale,
            );
        }

        return $productViewTransfer;
    }
}
