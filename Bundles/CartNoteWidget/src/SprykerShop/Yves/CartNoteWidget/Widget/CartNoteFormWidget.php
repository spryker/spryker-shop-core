<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\CartNoteWidget\CartNoteWidgetFactory getFactory()
 */
class CartNoteFormWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addParameter('cartNoteForm', $this->getCartNoteForm($quoteTransfer)->createView())
            ->addParameter('cart', $quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CartNoteFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartNoteWidget/views/cart-note-form/cart-note-form.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getCartNoteForm(QuoteTransfer $quoteTransfer): FormInterface
    {
        $cartNoteForm = $this->getFactory()
            ->getCartNoteQuoteForm()
            ->setData($quoteTransfer);

        return $cartNoteForm;
    }
}
