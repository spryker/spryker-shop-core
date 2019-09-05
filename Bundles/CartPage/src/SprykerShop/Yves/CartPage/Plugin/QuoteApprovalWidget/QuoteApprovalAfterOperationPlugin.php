<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin\QuoteApprovalWidget;

use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuoteApprovalWidgetExtension\Dependency\Plugin\QuoteApprovalAfterOperationPluginInterface;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class QuoteApprovalAfterOperationPlugin extends AbstractPlugin implements QuoteApprovalAfterOperationPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Updates session quote with quote approvals.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return void
     */
    public function execute(QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer): void
    {
        $this->getFactory()
            ->createQuoteApprovalToQuoteSynchronizer()
            ->synchronizeQuoteApprovals($quoteApprovalResponseTransfer);
    }
}
