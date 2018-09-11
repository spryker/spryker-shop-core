<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Dependency\Plugin\ShoppingListWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface ShoppingListWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ShoppingListWidgetPlugin';

    /**
     * @api
     *
     * @return void
     */
    public function initialize(): void;
}
