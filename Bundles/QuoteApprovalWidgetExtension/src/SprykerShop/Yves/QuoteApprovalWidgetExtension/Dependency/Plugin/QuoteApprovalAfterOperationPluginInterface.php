<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidgetExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;

interface QuoteApprovalAfterOperationPluginInterface
{
    /**
     * Specification:
     *  - Synchronize quote approval changes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer
     *
     * @return void
     */
    public function execute(QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer): void;
}
