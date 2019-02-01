<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Widget;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory()
 */
class QuoteApprovalWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addParameter('quoteTransfer', $quoteTransfer);
        $this->addParameter('quoteOwner', $this->getQuoteOwner($quoteTransfer));
        $this->addParameter('waitingQuoteApproval', $this->getWaitingQuoteApprovalByCurrentCompanyUser($quoteTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'QuoteApprovalWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@QuoteApprovalWidget/views/quote-approval-widget/quote-approval-widget.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getQuoteOwner(QuoteTransfer $quoteTransfer): CustomerTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($quoteTransfer->getCustomerReference());

        $customerResponseTransfer = $this->getFactory()
            ->getCustomerClient()
            ->findCustomerByReference($customerTransfer);

        $customerResponseTransfer->requireCustomerTransfer();

        return $customerResponseTransfer->getCustomerTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function getCurrentCompanyUser(): ?CompanyUserTransfer
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if (!$customerTransfer) {
            return null;
        }

        return $customerTransfer->getCompanyUserTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer|null
     */
    protected function getWaitingQuoteApprovalByCurrentCompanyUser(QuoteTransfer $quoteTransfer): ?QuoteApprovalTransfer
    {
        if (!$this->getCurrentCompanyUser()) {
            return null;
        }

        return $this->getFactory()
            ->getQuoteApprovalClient()
            ->getWaitingQuoteApprovalByIdCompanyUser(
                $quoteTransfer,
                $this->getCurrentCompanyUser()
                    ->getIdCompanyUser()
            );
    }
}
