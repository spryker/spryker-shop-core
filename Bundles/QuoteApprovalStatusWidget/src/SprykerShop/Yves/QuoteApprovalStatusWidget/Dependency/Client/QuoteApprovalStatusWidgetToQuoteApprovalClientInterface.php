<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalStatusWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteApprovalStatusWidgetToQuoteApprovalClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return null|string
     */
    public function getQuoteStatus(QuoteTransfer $quoteTransfer): ?string; //Todo: update return type according to latest changes in PS-4362.
}
