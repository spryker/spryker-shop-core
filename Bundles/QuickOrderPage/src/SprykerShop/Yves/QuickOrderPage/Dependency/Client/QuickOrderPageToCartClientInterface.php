<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuickOrderPageToCartClientInterface
{
    /**
     * @return void
     */
    public function clearQuote(): void;

    /**
     * @param array $itemTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItems(array $itemTransfers): QuoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function storeQuote(QuoteTransfer $quoteTransfer): void;
}
