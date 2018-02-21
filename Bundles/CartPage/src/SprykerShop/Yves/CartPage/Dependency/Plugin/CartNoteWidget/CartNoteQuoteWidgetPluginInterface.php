<?php

namespace SprykerShop\Yves\CartPage\Dependency\Plugin\CartNoteWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CartNoteQuoteWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'CartNoteQuoteWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void;
}
