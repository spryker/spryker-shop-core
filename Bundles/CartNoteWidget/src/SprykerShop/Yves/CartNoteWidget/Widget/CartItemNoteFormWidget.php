<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\CartNoteWidget\CartNoteWidgetFactory getFactory()
 */
class CartItemNoteFormWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer)
    {
        $this->addParameter('cartNoteForm', $this->getCartNoteForm($itemTransfer)->createView())
            ->addParameter('cart', $quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CartItemNoteFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartNoteWidget/views/cart-item-note-form/cart-item-note-form.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getCartNoteForm(ItemTransfer $itemTransfer): FormInterface
    {
        $cartNoteForm = $this->getFactory()
            ->getCartNoteQuoteItemForm()
            ->setData($itemTransfer);

        return $cartNoteForm;
    }
}
