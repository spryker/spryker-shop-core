<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Plugin\CartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CartNoteWidget\CartNoteQuoteItemWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\CartNoteWidget\CartNoteWidgetFactory getFactory()
 */
class CartNoteQuoteItemWidgetPlugin extends AbstractWidgetPlugin implements CartNoteQuoteItemWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): void
    {
        $cartNoteForm = $this->getFactory()->getCartNoteQuoteItemForm();
        $cartNoteForm->setData($itemTransfer);
        $this->addParameter('cartNoteForm', $cartNoteForm->createView());
        $this->addParameter('cart', $quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName()
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
    public static function getTemplate()
    {
        return '@CartNoteWidget/views/cart-page/cart-note-item-form.twig';
    }
}
