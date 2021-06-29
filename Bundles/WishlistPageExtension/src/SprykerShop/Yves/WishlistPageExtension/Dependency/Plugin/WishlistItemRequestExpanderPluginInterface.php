<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\WishlistItemTransfer;

/**
 * Provides ability to expand `WishlistItem` by provided request data.
 */
interface WishlistItemRequestExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `WishlistItem` by provided params.
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
    public function expand(WishlistItemTransfer $wishlistItemTransfer, array $params): WishlistItemTransfer;
}
