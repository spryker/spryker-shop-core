<?php

namespace SprykerShop\Yves\CustomerPage\Dependency\Plugin\CartNoteWidget;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CartNoteOrderNoteWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'CartNoteOrderNoteWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function initialize(OrderTransfer $orderTransfer): void;
}
