<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Extractor;

use Generated\Shared\Transfer\QuoteRequestTransfer;

interface ExpenseExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer[]
     */
    public function extractShipmentExpenses(QuoteRequestTransfer $quoteRequestTransfer): array;
}
