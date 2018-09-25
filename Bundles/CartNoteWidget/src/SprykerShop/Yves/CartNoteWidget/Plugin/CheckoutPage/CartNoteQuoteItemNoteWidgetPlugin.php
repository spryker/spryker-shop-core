<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Plugin\CheckoutPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CheckoutPage\Dependency\Plugin\CartNoteWidget\CartNoteQuoteItemNoteWidgetPluginInterface;

/**
 * @deprecated Use NEW_MOLECULE instead.
 */
class CartNoteQuoteItemNoteWidgetPlugin extends AbstractWidgetPlugin implements CartNoteQuoteItemNoteWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void
    {
        $this->addParameter('item', $itemTransfer);
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartNoteWidget/views/checkout-cart-item-note-display/checkout-cart-item-note-display.twig';
    }
}
