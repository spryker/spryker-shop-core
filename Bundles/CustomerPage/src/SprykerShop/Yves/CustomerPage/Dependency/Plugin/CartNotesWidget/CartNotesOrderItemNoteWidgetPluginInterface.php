<?php

namespace SprykerShop\Yves\CustomerPage\Dependency\Plugin\CartNotesWidget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CartNotesOrderItemNoteWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'CartNotesOrderItemNoteWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void;
}
