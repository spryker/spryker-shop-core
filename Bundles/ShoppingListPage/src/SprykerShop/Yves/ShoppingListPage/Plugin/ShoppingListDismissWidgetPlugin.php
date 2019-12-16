<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Plugin;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShoppingListPage\Widget\ShoppingListDismissWidget;

/**
 * @deprecated Use SprykerShop\Yves\ShoppingListPage\Widget\ShoppingListDismissWidget instead.
 *
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 */
class ShoppingListDismissWidgetPlugin extends AbstractWidgetPlugin
{
    public const NAME = 'ShoppingListDismissWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function initialize(ShoppingListTransfer $shoppingListTransfer): void
    {
        $widget = new ShoppingListDismissWidget($shoppingListTransfer);

        $this->parameters = $widget->getParameters();
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return ShoppingListDismissWidget::getTemplate();
    }
}
