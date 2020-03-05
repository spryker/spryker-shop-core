<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Checker;

use Generated\Shared\Transfer\QuoteRequestTransfer;

interface QuoteCheckerInterface
{
    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteLevelShipmentUsed(QuoteRequestTransfer $quoteRequestTransfer): bool;
}
