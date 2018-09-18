<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Dependency\Plugin\CartNoteWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CartNoteQuoteNoteWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'CartNoteQuoteNoteWidgetPlugin';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void;
}
