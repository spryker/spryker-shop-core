<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ShoppingListPageConfig extends AbstractBundleConfig
{
    /**
     * @use \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    public const CART_REDIRECT_URL = 'cart';
}
