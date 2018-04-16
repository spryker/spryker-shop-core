<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

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
