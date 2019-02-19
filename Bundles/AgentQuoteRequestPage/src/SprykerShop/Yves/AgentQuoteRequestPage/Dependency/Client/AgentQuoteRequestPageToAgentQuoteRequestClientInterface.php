<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;

interface AgentQuoteRequestPageToAgentQuoteRequestClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelByReference(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer;
}
