<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Handler;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;

interface QuoteRequestCartHandlerInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequestQuote(): QuoteRequestResponseTransfer;
}
