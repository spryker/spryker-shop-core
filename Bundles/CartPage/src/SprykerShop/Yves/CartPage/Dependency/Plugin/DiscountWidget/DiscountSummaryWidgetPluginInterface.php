<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Dependency\Plugin\DiscountWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface DiscountSummaryWidgetPluginInterface extends WidgetPluginInterface
{

    const NAME = 'DiscountSummaryWidgetPlugin';

    /**
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void;
}
