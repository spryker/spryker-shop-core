<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Plugin\ShoppingListPage;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;

/**
 * @deprecated Use molecule('shopping-list-item-product-option-display', 'ProductOptionWidget') instead.
 *
 * @method \SprykerShop\Yves\ProductOptionWidget\ProductOptionWidgetFactory getFactory()
 */
class ShoppingListItemProductOptionWidgetPlugin extends AbstractWidgetPlugin
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function initialize(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $this->addParameter(
            'item',
            $shoppingListItemTransfer
        );
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
        return 'ShoppingListItemProductOptionWidgetPlugin';
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
        return '@ProductOptionWidget/views/shopping-list-item-product-option-display/shopping-list-item-product-option-display.twig';
    }
}
