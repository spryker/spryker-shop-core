<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\Plugin\Permission\PlaceOrderPermissionPlugin;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory()
 */
class QuoteApproveRequestWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $form = $this->getFactory()->createQuoteApproveRequestForm($quoteTransfer);

        $quoteApprovalStatus = $this->getFactory()
            ->createQuoteApprovalStatusCalculator()
            ->calculateQuoteStatus($quoteTransfer);

        $this->addParameter('isVisible', $this->isVisible());
        $this->addParameter('form', $form->createView());
        $this->addParameter('quote', $form->getData()->getQuote());
        $this->addParameter('quoteApprovalStatus', $quoteApprovalStatus);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'QuoteApproveRequestWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@QuoteApprovalWidget/views/quote-approve-request/quote-approve-request.twig';
    }

    /**
     * @return bool
     */
    protected function isVisible(): bool
    {
        return $this->getFactory()->getPermissionClient()->findCustomerPermissionByKey(
            PlaceOrderPermissionPlugin::KEY
        ) !== null;
    }
}
