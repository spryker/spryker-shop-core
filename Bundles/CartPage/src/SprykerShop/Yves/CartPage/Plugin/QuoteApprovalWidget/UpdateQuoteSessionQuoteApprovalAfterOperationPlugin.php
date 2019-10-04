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
class UpdateQuoteSessionQuoteApprovalAfterOperationPlugin extends AbstractPlugin implements QuoteApprovalAfterOperationPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Saves quote approvals to session quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer
     *
     * @return void
     */
    public function execute(QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer): void
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        $quoteTransfer->setQuoteApprovals($quoteApprovalResponseTransfer->getQuote()->getQuoteApprovals());
        $quoteTransfer->setIsLocked($quoteApprovalResponseTransfer->getQuote()->getIsLocked());

        $this->getFactory()
            ->getQuoteClient()
            ->setQuote($quoteTransfer);
    }
}
