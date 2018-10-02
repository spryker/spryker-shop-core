<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteResponseTransfer;

interface MultiCartPageToCartClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote(): QuoteResponseTransfer;
}
