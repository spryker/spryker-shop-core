<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuoteApprovalWidgetExtension\Dependency\Plugin\HideBreadcrumbItemPluginInterface;

/**
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory()
 */
class AddressStepHideBreadcrumbItemPlugin extends AbstractPlugin implements HideBreadcrumbItemPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(QuoteTransfer $quoteTransfer): bool
    {
        $isQuoteLocked = $this->getFactory()
            ->getQuoteClient()
            ->isQuoteLocked($quoteTransfer);

        $isQuoteDeclined = $this->getFactory()
            ->getQuoteApprovalClient()
            ->isQuoteDeclined($quoteTransfer);

        return $isQuoteLocked && !$isQuoteDeclined;
    }
}
