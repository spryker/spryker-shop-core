<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget\Plugin\WishlistPage;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\WishlistPageExtension\Dependency\Plugin\WishlistItemRequestExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetFactory getFactory()
 */
class ProductConfigurationWishlistItemRequestExpanderPlugin extends AbstractPlugin implements WishlistItemRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `WishlistItem` with product configuration.
     * - If `has_product_configuration_attached=1` param is provided, expands `WishlistItem` with `ProductConfigurationInstance` stored in `WishlistItem`.
     * - Tries to expand `WishlistItem` with `ProductConfigurationInstance` by SKU otherwise.
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
            ->getProductConfigurationWishlistClient()
            ->expandWithProductConfiguration($wishlistItemTransfer, $params);
    }
}
