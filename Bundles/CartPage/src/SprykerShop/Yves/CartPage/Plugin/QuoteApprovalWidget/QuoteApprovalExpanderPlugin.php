<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin\QuoteApprovalWidget;

use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuoteApprovalWidgetExtension\Dependency\Plugin\QuoteApprovalExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class QuoteApprovalExpanderPlugin extends AbstractPlugin implements QuoteApprovalExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Updates session quote with quote approvals.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     * @param int|null $idQuoteApproval
     *
     * @return void
     */
    public function execute(QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer, ?int $idQuoteApproval = null): void
    {
        $this->getFactory()
            ->createQuoteApprovalExpander()
            ->expandQuoteApprovals($quoteApprovalResponseTransfer, $idQuoteApproval);
    }
}
