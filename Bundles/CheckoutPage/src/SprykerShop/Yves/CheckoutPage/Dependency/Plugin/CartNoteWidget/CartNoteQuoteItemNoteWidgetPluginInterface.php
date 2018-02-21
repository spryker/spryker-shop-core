<?php

namespace SprykerShop\Yves\CheckoutPage\Dependency\Plugin\CartNoteWidget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CartNoteQuoteItemNoteWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'CartNoteQuoteItemNoteWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void;
}
