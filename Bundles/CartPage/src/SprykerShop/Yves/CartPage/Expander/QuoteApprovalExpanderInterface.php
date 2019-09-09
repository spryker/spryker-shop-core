<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Expander;

use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;

interface QuoteApprovalExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer
     * @param int|null $idQuoteApproval
     *
     * @return void
     */
    public function expandQuoteApprovals(
        QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer,
        ?int $idQuoteApproval = null
    ): void;
}
