<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;

interface QuoteRequestAgentPageToQuoteRequestClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByFilter(
        QuoteRequestFilterTransfer $quoteRequestFilterTransfer
    ): QuoteRequestCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer
     */
    public function getQuoteRequestVersionCollectionByFilter(
        QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer
    ): QuoteRequestVersionCollectionTransfer;
}
