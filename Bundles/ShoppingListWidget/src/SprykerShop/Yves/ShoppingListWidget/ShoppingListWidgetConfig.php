<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ShoppingListWidgetConfig extends AbstractBundleConfig
{
    protected const DEFAULT_NAME = 'My shopping list';

    /**
     * @see \SprykerShop\Yves\ShoppingListPage\Plugin\Provider\ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_DETAILS
     */
    public const SHOPPING_LIST_REDIRECT_URL = 'shopping-list/details';

    /**
     * @return string
     */
    public function getShoppingListDefaultName(): string
    {
        return static::DEFAULT_NAME;
    }
}
