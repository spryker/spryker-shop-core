<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;

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
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function approveQuote(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->quoteApprovalClient->approveQuote($quoteApprovalRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function declineQuote(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->quoteApprovalClient->declineQuote($quoteApprovalRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function cancelQuote(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->quoteApprovalClient->cancelQuote($quoteApprovalRequestTransfer);
    }
}
