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
    public const DEFAULT_ITEMS_PER_PAGE = 10;

    public const CART_REDIRECT_URL = 'cart';

    public const MIN_QUANTITY_RANGE = 1;

    /**
     * @return string
     */
    public function getShoppingListDefaultName(): string
    {
        return static::DEFAULT_NAME;
    }

    /**
     * @return string
     */
    public function getShoppingListDefaultItemsPerPage(): string
    {
        return static::DEFAULT_ITEMS_PER_PAGE;
    }
}
