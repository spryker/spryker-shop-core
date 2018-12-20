<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Controller;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory()
 */
class QuoteApprovalController extends AbstractController
{
    protected const REFERER_PARAM = 'referer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idQuoteApproval
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function approveAction(Request $request, int $idQuoteApproval)
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            return $this->redirectBack($request);
        }

        $quoteApprovalRequestTransfer = (new QuoteApprovalRequestTransfer())
            ->setIdQuoteApproval($idQuoteApproval)
            ->setFkCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        $quoteApprovalResponseTransfer = $this->getFactory()
            ->getQuoteApprovalClient()
            ->approveQuote($quoteApprovalRequestTransfer);

        if (!$quoteApprovalResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage('quote_approval_widget.cart.error_approve_message');

            return $this->redirectBack($request);
        }

        $this->addSuccessMessage('quote_approval_widget.cart.success_approve_message');

        return $this->redirectBack($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idQuoteApproval
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function declineAction(Request $request, int $idQuoteApproval)
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            return $this->redirectBack($request);
        }

        $quoteApprovalRequestTransfer = (new QuoteApprovalRequestTransfer())
            ->setIdQuoteApproval($idQuoteApproval)
            ->setFkCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        $quoteApprovalResponseTransfer = $this->getFactory()
            ->getQuoteApprovalClient()
            ->declineQuote($quoteApprovalRequestTransfer);

        if (!$quoteApprovalResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage('quote_approval_widget.cart.error_decline_message');

            return $this->redirectBack($request);
        }

        $this->addSuccessMessage('quote_approval_widget.cart.success_decline_message');

        return $this->redirectBack($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idQuoteApproval
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cancelAction(Request $request, int $idQuoteApproval)
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            return $this->redirectBack($request);
        }

        $quoteApprovalRequestTransfer = (new QuoteApprovalRequestTransfer())
            ->setIdQuoteApproval($idQuoteApproval)
            ->setFkCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        $quoteApprovalResponseTransfer = $this->getFactory()
            ->getQuoteApprovalClient()
            ->cancelQuote($quoteApprovalRequestTransfer);

        if (!$quoteApprovalResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage('quote_approval_widget.cart.error_cancel_message');

            return $this->redirectBack($request);
        }

        $this->addSuccessMessage('quote_approval_widget.cart.success_cancel_message');

        return $this->redirectBack($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectBack(Request $request): RedirectResponse
    {
        $referer = $request->headers->get(static::REFERER_PARAM);

        return $this->redirectResponseExternal($referer);
    }
}
