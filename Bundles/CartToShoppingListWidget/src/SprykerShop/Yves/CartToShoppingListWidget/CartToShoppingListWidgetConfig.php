<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartToShoppingListWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CartToShoppingListWidgetConfig extends AbstractBundleConfig
{
    /**
     * @use \SprykerShop\Yves\ShoppingListPage\Plugin\Provider\ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_DETAILS
     */
    public const SHOPPING_LIST_REDIRECT_URL = 'shopping-list/details';

    /**
     * @use \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    public const CART_REDIRECT_URL = 'cart';
}
