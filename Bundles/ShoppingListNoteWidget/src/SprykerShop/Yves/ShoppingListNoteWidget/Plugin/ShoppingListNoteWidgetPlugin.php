<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListNoteWidget\Plugin;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShoppingListPage\Plugin\ShoppingListItemNoteWidgetPluginInterface;

class ShoppingListNoteWidgetPlugin extends AbstractWidgetPlugin implements ShoppingListItemNoteWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function initialize(ShoppingListTransfer $shoppingListTransfer, int $idShoppingListItem): void
    {
        $this->addParameter('shoppingListItem', $this->getShoppingListItem($shoppingListTransfer, $idShoppingListItem));
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListNoteWidget/views/shopping-list-item-note/shopping-list-item-note.twig';
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer|null
     */
    protected function getShoppingListItem(ShoppingListTransfer $shoppingListTransfer, int $idShoppingListItem): ?ShoppingListItemTransfer
    {
        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            if ($shoppingListItemTransfer->getIdShoppingListItem() === $idShoppingListItem) {
                return $shoppingListItemTransfer;
            }
        }

        return null;
    }
}
