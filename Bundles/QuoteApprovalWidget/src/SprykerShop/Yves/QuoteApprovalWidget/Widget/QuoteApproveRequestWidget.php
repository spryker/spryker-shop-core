<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Widget;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory()
 */
class QuoteApproveRequestWidget extends AbstractWidget
{
    use PermissionAwareTrait;

    protected const PARAMETER_QUOTE = 'quote';
    protected const PARAMETER_QUOTE_STATUS = 'quoteStatus';
    protected const PARAMETER_QUOTE_APPROVAL_REQUEST_FROM = 'quoteApprovalRequestForm';
    protected const PARAMETER_CAN_SEND_APPROVAL_REQUEST = 'canSendApprovalRequest';
    protected const PARAMETER_LIMIT = 'limit';
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addIsVisibleParameter($quoteTransfer);
        $this->addQuoteParameter($quoteTransfer);
        $this->addQuoteStatusParameter($quoteTransfer);
        $this->addLimitParameter($quoteTransfer);
        $this->addCanSendApprovalRequestParameter($quoteTransfer);
        $this->addQuoteApprovalRequestFormParameter($quoteTransfer);
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
     * @return void
     */
    protected function addQuoteParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(static::PARAMETER_QUOTE, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addQuoteStatusParameter(QuoteTransfer $quoteTransfer): void
    {
        if (!$this->isWidgetVisible($quoteTransfer)) {
            $this->addParameter(static::PARAMETER_QUOTE_STATUS, null);

            return;
        }

        $this->addParameter(static::PARAMETER_QUOTE_STATUS, $this->getQuoteApprovalClient()->calculateQuoteStatus($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addQuoteApprovalRequestFormParameter(QuoteTransfer $quoteTransfer): void
    {
        if (!$this->isWidgetVisible($quoteTransfer)) {
            $this->addParameter(static::PARAMETER_QUOTE_APPROVAL_REQUEST_FROM, null);

            return;
        }

        $this->addParameter(static::PARAMETER_QUOTE_APPROVAL_REQUEST_FROM, $this->createQuoteApprovalRequestForm($quoteTransfer)->createView());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addCanSendApprovalRequestParameter(QuoteTransfer $quoteTransfer): void
    {
        if (!$this->isWidgetVisible($quoteTransfer)) {
            $this->addParameter(static::PARAMETER_CAN_SEND_APPROVAL_REQUEST, null);

            return;
        }

        $this->addParameter(
            static::PARAMETER_CAN_SEND_APPROVAL_REQUEST,
            !$this->getQuoteApprovalClient()->isQuoteApproved($quoteTransfer)
            && !$this->getQuoteApprovalClient()->isQuoteWaitingForApproval($quoteTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addLimitParameter(QuoteTransfer $quoteTransfer): void
    {
        if (!$this->isWidgetVisible($quoteTransfer)) {
            $this->addParameter(static::PARAMETER_LIMIT, null);

            return;
        }

        $this->addParameter(static::PARAMETER_LIMIT, $this->getLimitForQuote($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addIsVisibleParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, $this->isWidgetVisible($quoteTransfer));
    }

    /**
     * @uses \Spryker\Shared\QuoteApproval\Plugin\RequestQuoteApprovalPermissionPlugin
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isWidgetVisible(QuoteTransfer $quoteTransfer): bool
    {
        $customerTransfer = $this->getCurrentCustomer();

        if ($customerTransfer === null) {
            return false;
        }

        if ($quoteTransfer->getCustomerReference() !== $customerTransfer->getCustomerReference()) {
            return false;
        }

        return $this->can('RequestQuoteApprovalPermissionPlugin');
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
            $this->getCurrentCompanyUser()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function getCurrentCompanyUser(): ?CompanyUserTransfer
    {
        $customerTransfer = $this->getCurrentCustomer();

        if (!$customerTransfer) {
            return null;
        }

        return $customerTransfer->getCompanyUserTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getCurrentCustomer(): ?CustomerTransfer
    {
        return $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();
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
