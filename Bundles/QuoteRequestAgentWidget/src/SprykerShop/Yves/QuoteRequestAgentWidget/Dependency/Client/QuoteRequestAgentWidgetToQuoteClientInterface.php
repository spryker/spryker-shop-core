<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteRequestAgentWidgetToQuoteClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote(): QuoteTransfer;
}
