<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Plugin;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ShoppingListNoteWidget\Widget\ShoppingListItemNoteWidget instead.
 */
interface ShoppingListItemNoteWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ShoppingListItemNoteWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function initialize(ShoppingListItemTransfer $shoppingListItemTransfer): void;
}
