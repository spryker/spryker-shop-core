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
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetConfig getConfig()
 */
class QuoteApprovalWidget extends AbstractWidget
{
    protected const PARAMETER_IS_VISIBLE = 'isVisible';
    protected const PARAMETER_IS_VISIBLE_ON_CART_PAGE = 'isVisibleOnCartPage';
    protected const PARAMETER_IS_QUOTE_APPLICABLE = 'isQuoteApplicable';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addParameter('quoteTransfer', $quoteTransfer);
        $this->addParameter('quoteOwner', $this->getQuoteOwner($quoteTransfer));
        $this->addParameter('waitingQuoteApproval', $this->getWaitingQuoteApprovalByCurrentCompanyUser($quoteTransfer));
        $this->addParameter('canQuoteBeApprovedByCurrentCustomer', $this->canQuoteBeApprovedByCurrentCustomer($quoteTransfer));
        $this->addIsVisibleParameter($quoteTransfer);
        $this->addIsQuoteApplicableParameter($quoteTransfer);
        $this->addIsWidgetVisibleOnCartPageParameter();
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
     * @return void
     */
    protected function addIsVisibleParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(
            static::PARAMETER_IS_VISIBLE,
            $this->hasQuoteApprovalsForCurrentCompanyUser($quoteTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getQuoteOwner(QuoteTransfer $quoteTransfer): ?CustomerTransfer
    {
        if (!$quoteTransfer->getCustomerReference()) {
            return null;
        }

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($quoteTransfer->getCustomerReference());

        $customerResponseTransfer = $this->getFactory()
            ->getCustomerClient()
            ->findCustomerByReference($customerTransfer);

        $customerResponseTransfer->requireCustomerTransfer();

        return $customerResponseTransfer->getCustomerTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer|null
     */
    protected function getWaitingQuoteApprovalByCurrentCompanyUser(QuoteTransfer $quoteTransfer): ?QuoteApprovalTransfer
    {
        if (!$this->findCurrentCompanyUser()) {
            return null;
        }

        return $this->getFactory()
            ->getQuoteApprovalClient()
            ->findWaitingQuoteApprovalByIdCompanyUser(
                $quoteTransfer,
                $this->findCurrentCompanyUser()
                    ->getIdCompanyUser()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasQuoteApprovalsForCurrentCompanyUser(QuoteTransfer $quoteTransfer): bool
    {
        if (!$this->findCurrentCompanyUser()) {
            return false;
        }

        return $this->getFactory()
            ->getQuoteApprovalClient()
            ->isCompanyUserInQuoteApproverList(
                $quoteTransfer,
                $this->findCurrentCompanyUser()
                    ->getIdCompanyUser()
            );
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function findCurrentCompanyUser(): ?CompanyUserTransfer
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
     * @return bool
     */
    protected function canQuoteBeApprovedByCurrentCustomer(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()->getQuoteApprovalClient()
            ->canQuoteBeApprovedByCurrentCustomer($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addIsQuoteApplicableParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(
            static::PARAMETER_IS_QUOTE_APPLICABLE,
            $this->getFactory()
                ->getQuoteApprovalClient()
                ->isQuoteApplicableForApprovalProcess($quoteTransfer)
        );
    }

    /**
     * @return void
     */
    protected function addIsWidgetVisibleOnCartPageParameter(): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE_ON_CART_PAGE, $this->getConfig()->isWidgetVisibleOnCartPage());
    }
}
