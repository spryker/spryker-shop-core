<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface PostReorderPluginInterface
{
    /**
     *  Specification:
     *  - Plugin is executed after reordering.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    public function execute(QuoteTransfer $quoteTransfer, array $itemTransfers): void;
}
