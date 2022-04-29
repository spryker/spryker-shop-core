<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Client;

interface QuickOrderPageToCartClientInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItems(array $itemTransfers, array $params = []);
}
