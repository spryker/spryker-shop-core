<?php

namespace SprykerShop\Yves\CheckoutPage\Dependency\Plugin\CartNotesWidget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CartNotesQuoteItemNoteWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'CartNotesQuoteItemNoteWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void;
}
