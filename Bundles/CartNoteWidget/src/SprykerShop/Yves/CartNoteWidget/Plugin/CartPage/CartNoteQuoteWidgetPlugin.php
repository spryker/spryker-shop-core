<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Plugin\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartNoteWidget\Widget\CartNoteFormWidget;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CartNoteWidget\CartNoteQuoteWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\CartNoteWidget\Widget\CartNoteFormWidget instead.
 *
 * @method \SprykerShop\Yves\CartNoteWidget\CartNoteWidgetFactory getFactory()
 */
class CartNoteQuoteWidgetPlugin extends AbstractWidgetPlugin implements CartNoteQuoteWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $widget = new CartNoteFormWidget($quoteTransfer);

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return CartNoteFormWidget::getTemplate();
    }
}
