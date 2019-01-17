<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface;
use Symfony\Component\Form\FormInterface;

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
        $isVisible = $this->getQuoteApprovalClient()->isQuoteRequireApproval($quoteTransfer);

        $this->addParameter('isVisible', $isVisible);

        if (!$isVisible) {
            return;
        }

        $this->addParameter('limit', $this->getLimitForQuote($quoteTransfer));
        $this->addParameter('canSendApprovalRequest', !$this->getQuoteApprovalClient()->isQuoteWaitingForApproval($quoteTransfer));
        $this->addParameter('quoteApprovalRequestForm', $this->createQuoteApprovalRequestForm($quoteTransfer)->createView());
        $this->addParameter('quote', $quoteTransfer);
        $this->addParameter('quoteStatus', $this->getQuoteApprovalClient()->calculateQuoteStatus($quoteTransfer));
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function getLimitForQuote(QuoteTransfer $quoteTransfer): ?int
    {
        return $this->getQuoteApprovalClient()->calculatePlaceOrderPermissionLimit(
            $quoteTransfer,
            $this->getFactory()->getCustomerClient()->getCustomer()->getCompanyUserTransfer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createQuoteApprovalRequestForm(QuoteTransfer $quoteTransfer): FormInterface
    {
        return $this->getFactory()->createQuoteApproveRequestForm(
            $quoteTransfer,
            $this->getLocale()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface
     */
    protected function getQuoteApprovalClient(): QuoteApprovalWidgetToQuoteApprovalClientInterface
    {
        return $this->getFactory()->getQuoteApprovalClient();
    }
}
