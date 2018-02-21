<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNotesWidget\Plugin\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CartNotesWidget\CartNotesQuoteWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\CartNotesWidget\CartNotesWidgetFactory getFactory()
 */
class CartNotesQuoteWidgetPlugin extends AbstractWidgetPlugin implements CartNotesQuoteWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $cartNotesForm = $this->getFactory()->getCartNotesQuoteForm();
        $cartNotesForm->setData($quoteTransfer);
        $this->addParameter('cartNotesForm', $cartNotesForm->createView());
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
        return '@CartNotesWidget/_cart-page/cart-notes-quote-form.twig';
    }
}
