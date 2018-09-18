<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Plugin\ShoppingListWidget;

interface ShoppingListWidgetPluginInterface
{
    public const NAME = 'ShoppingListQuickOrderWidgetPlugin';

    /**
     * @api
     *
     * @return void
     */
    public function initialize(): void;
}
