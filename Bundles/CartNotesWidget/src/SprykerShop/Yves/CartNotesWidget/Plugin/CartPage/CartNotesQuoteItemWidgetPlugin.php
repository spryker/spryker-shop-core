<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNotesWidget\Plugin\CartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CartNotesWidget\CartNotesQuoteItemWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\CartNotesWidget\CartNotesWidgetFactory getFactory()
 */
class CartNotesQuoteItemWidgetPlugin extends AbstractWidgetPlugin implements CartNotesQuoteItemWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void
    {
        $cartNotesForm = $this->getFactory()->getCartNotesQuoteItemForm();
        $cartNotesForm->setData($itemTransfer);
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
        return '@CartNotesWidget/_cart-page/cart-notes-item-form.twig';
    }
}
