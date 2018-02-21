<?php

namespace SprykerShop\Yves\CheckoutPage\Dependency\Plugin\CartNotesWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CartNotesQuoteNoteWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'CartNotesQuoteNoteWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void;
}
