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
     * @param int $quantity
     *
     * @return void
     */
    public function initialize(int $quantity): void;
}
