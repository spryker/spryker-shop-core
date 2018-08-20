<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Plugin;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface ShoppingListItemNoteWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ShoppingListItemNoteWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function initialize(ShoppingListTransfer $shoppingListTransfer, int $idShoppingListItem): void;
}
