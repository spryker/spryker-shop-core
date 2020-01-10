<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListNoteWidget\Plugin;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShoppingListNoteWidget\Widget\ShoppingListItemNoteWidget;
use SprykerShop\Yves\ShoppingListPage\Plugin\ShoppingListItemNoteWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ShoppingListNoteWidget\Widget\ShoppingListItemNoteWidget instead.
 */
class ShoppingListItemNoteWidgetPlugin extends AbstractWidgetPlugin implements ShoppingListItemNoteWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function initialize(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $widget = new ShoppingListItemNoteWidget($shoppingListItemTransfer);

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return ShoppingListItemNoteWidget::getTemplate();
    }

    /**
     * {@inheritDoc}
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
