<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ShoppingListPageConfig extends AbstractBundleConfig
{
    public const DEFAULT_NAME = 'My shopping list';
    public const CART_REDIRECT_URL = 'cart';
    public const MIN_QUANTITY_RANGE = 1;

    /**
     * Specification:
     * - Returns the cart redirect url.
     *
     * @return string
     */
    public function getCartRedirectUrl(): string
    {
        return static::CART_REDIRECT_URL;
    }

    /**
     * Specification:
     * - Returns the minimum quantity range.
     *
     * @return int
     */
    public function getMinimumQuantityRange(): int
    {
        return static::MIN_QUANTITY_RANGE;
    }
}
