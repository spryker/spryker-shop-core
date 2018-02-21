<?php

namespace SprykerShop\Yves\CartPage\Dependency\Plugin\CartNotesWidget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CartNotesQuoteItemWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'CartNotesQuoteItemWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void;
}
