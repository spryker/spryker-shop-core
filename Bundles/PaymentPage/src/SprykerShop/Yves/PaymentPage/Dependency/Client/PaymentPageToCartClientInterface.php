<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

interface PaymentPageToCartClientInterface
{
    /**
     * @return void
     */
    public function clearQuote(): void;

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote(): QuoteTransfer;
}
