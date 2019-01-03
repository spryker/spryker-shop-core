<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteApprovalWidgetToQuoteApprovalClientBridge implements QuoteApprovalWidgetToQuoteApprovalClientInterface
{
    /**
     * @var \Spryker\Client\QuoteApproval\QuoteApprovalClientInterface
     */
    protected $quoteApprovalClient;

    /**
     * @param \Spryker\Client\QuoteApproval\QuoteApprovalClientInterface $quoteApprovalClient
     */
    public function __construct($quoteApprovalClient)
    {
        $this->quoteApprovalClient = $quoteApprovalClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function getQuoteStatus(QuoteTransfer $quoteTransfer): ?string //Todo: update return type according to latest changes in PS-4362.
    {
        return $this->quoteApprovalClient->getQuoteStatus($quoteTransfer);
    }
}
