<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListNoteWidget\Plugin;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShoppingListPage\Plugin\ShoppingListItemNoteWidgetPluginInterface;

class ShoppingListNoteWidgetPlugin extends AbstractWidgetPlugin implements ShoppingListItemNoteWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function initialize(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $this->addParameter('note', $shoppingListItemTransfer->getShoppingListItemNote()->getNote());
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
}
