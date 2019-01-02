<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalStatusWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\QuoteApprovalStatusWidget\QuoteApprovalStatusWidgetFactory getFactory()
 */
class QuoteApprovalStatusWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addParameter('quoteApprovalStatus', $this->getQuoteApprovalStatus($quoteTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'QuoteApprovalStatusWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@QuoteApprovalStatusWidget/views/quote-approval-status-widget/quote-approval-status-widget.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function getQuoteApprovalStatus(QuoteTransfer $quoteTransfer): ?string
    {
        return $this->getFactory()
            ->getQuoteApprovalClient()
            ->getQuoteStatus($quoteTransfer);
    }
}
