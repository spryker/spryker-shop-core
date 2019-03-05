<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget\Handler;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;

interface AgentQuoteRequestCartHandlerInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(): QuoteRequestResponseTransfer;
}
