<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteRequestAgentPageToCartClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateSpecificQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote();
}
