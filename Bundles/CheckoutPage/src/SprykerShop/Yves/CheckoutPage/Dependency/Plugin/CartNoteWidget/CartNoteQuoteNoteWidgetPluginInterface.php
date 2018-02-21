<?php

namespace SprykerShop\Yves\CheckoutPage\Dependency\Plugin\CartNoteWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CartNoteQuoteNoteWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'CartNoteQuoteNoteWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void;
}
