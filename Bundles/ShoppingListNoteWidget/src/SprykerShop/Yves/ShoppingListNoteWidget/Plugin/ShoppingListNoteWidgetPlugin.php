<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListNoteWidget\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShoppingListPage\Plugin\ShoppingListItemNoteWidgetPluginInterface;

class ShoppingListNoteWidgetPlugin extends AbstractWidgetPlugin implements ShoppingListItemNoteWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, ShoppingListTransfer $shoppingListTransfer): void
    {
        $this->addParameter('note', $this->getShoppingListItemNote($productViewTransfer, $shoppingListTransfer));
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListNoteWidget/views/shopping-list-item-note/shopping-list-item-note.twig';
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer
     */
    public function getShoppingListItemNote(ProductViewTransfer $productViewTransfer, ShoppingListTransfer $shoppingListTransfer): ShoppingListItemNoteTransfer
    {
        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            if ($shoppingListItemTransfer->getSku() === $productViewTransfer->getSku()) {
                return $shoppingListItemTransfer->getNote();
            }
        }

        return new ShoppingListItemNoteTransfer();
    }
}
