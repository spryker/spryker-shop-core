<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @deprecated Use {@link \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPostReorderPluginInterface} instead.
 */
interface PostReorderPluginInterface
{
    /**
     * Specification:
     * - Plugin is executed after reorder finished cart updating.
     * - Parameter `$itemTransfers` represents the items being reordered before sanitization.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    public function execute(QuoteTransfer $quoteTransfer, array $itemTransfers): void;
}
