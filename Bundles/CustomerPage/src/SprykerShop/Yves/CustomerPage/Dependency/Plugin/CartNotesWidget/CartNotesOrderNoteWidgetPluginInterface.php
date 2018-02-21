<?php

namespace SprykerShop\Yves\CustomerPage\Dependency\Plugin\CartNotesWidget;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CartNotesOrderNoteWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'CartNotesOrderNoteWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function initialize(OrderTransfer $orderTransfer): void;
}
