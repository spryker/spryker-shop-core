<?php

namespace SprykerShop\Yves\CartPage\Dependency\Plugin\CartNotesWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CartNotesQuoteWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'CartNotesQuoteWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void;
}
