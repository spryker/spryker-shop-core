<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Controller;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory()
 */
class QuoteApprovalController extends AbstractController
{
    protected const PARAM_REFERER = 'referer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createQuoteApprovalAction(Request $request): RedirectResponse
    {
        $quoteApproveRequestForm = $this->getFactory()
            ->createQuoteApproveRequestForm(
                $this->getFactory()->getQuoteClient()->getQuote(),
                $this->getLocale()
            );

        $quoteApproveRequestForm->handleRequest($request);

        if ($quoteApproveRequestForm->isSubmitted() && $quoteApproveRequestForm->isValid()) {
            $quoteApprovalResponseTransfer = $this->getFactory()
                ->getQuoteApprovalClient()
                ->createQuoteApproval($quoteApproveRequestForm->getData());

            $this->updateSessionQuoteOnSuccessfulResponse($quoteApprovalResponseTransfer);
            $this->addMessagesFromQuoteApprovalResponse($quoteApprovalResponseTransfer);
        }

        return $this->redirectToReferer($request);
    }

    /**
     * @param int $idQuoteApproval
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeQuoteApprovalAction(int $idQuoteApproval, Request $request): RedirectResponse
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer) {
            return $this->redirectToReferer($request);
        }
        $customerTransfer->requireCompanyUserTransfer();

        $quoteApprovalRequestTransfer = new QuoteApprovalRequestTransfer();

        $quoteApprovalRequestTransfer
            ->setIdQuoteApproval($idQuoteApproval)
            ->setRequesterCompanyUserId($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        $quoteApprovalResponseTransfer = $this->getFactory()
            ->getQuoteApprovalClient()
            ->removeQuoteApproval($quoteApprovalRequestTransfer);

        $this->updateSessionQuoteOnSuccessfulResponse($quoteApprovalResponseTransfer);
        $this->addMessagesFromQuoteApprovalResponse($quoteApprovalResponseTransfer);

        return $this->redirectToReferer($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idQuoteApproval
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function approveAction(Request $request, int $idQuoteApproval): RedirectResponse
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            return $this->redirectToReferer($request);
        }

        $quoteApprovalRequestTransfer = (new QuoteApprovalRequestTransfer())
            ->setIdQuoteApproval($idQuoteApproval)
            ->setQuote($this->getFactory()->getQuoteClient()->getQuote())
            ->setApproverCompanyUserId($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        $quoteApprovalResponseTransfer = $this->getFactory()
            ->getQuoteApprovalClient()
            ->approveQuoteApproval($quoteApprovalRequestTransfer);

        $this->updateSessionQuoteOnSuccessfulResponse($quoteApprovalResponseTransfer);
        $this->addMessagesFromQuoteApprovalResponse($quoteApprovalResponseTransfer);

        return $this->redirectToReferer($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idQuoteApproval
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function declineAction(Request $request, int $idQuoteApproval): RedirectResponse
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            return $this->redirectToReferer($request);
        }

        $quoteApprovalRequestTransfer = (new QuoteApprovalRequestTransfer())
            ->setIdQuoteApproval($idQuoteApproval)
            ->setApproverCompanyUserId($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        $quoteApprovalResponseTransfer = $this->getFactory()
            ->getQuoteApprovalClient()
            ->declineQuoteApproval($quoteApprovalRequestTransfer);

        $this->updateSessionQuoteOnSuccessfulResponse($quoteApprovalResponseTransfer);
        $this->addMessagesFromQuoteApprovalResponse($quoteApprovalResponseTransfer);

        return $this->redirectToReferer($request);
    }

    /**
     * @param string $key
     * @param array $params
     *
     * @return string
     */
    protected function getTranslatedMessage(string $key, array $params = []): string
    {
        return $this->getFactory()
            ->getGlossaryStorageClient()
            ->translate($key, $this->getLocale(), $params);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer
     *
     * @return void
     */
    protected function addMessagesFromQuoteApprovalResponse(QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer): void
    {
        foreach ($quoteApprovalResponseTransfer->getMessages() as $messageTransfer) {
            $translatedMessage = $this->getTranslatedMessage($messageTransfer->getValue(), $messageTransfer->getParameters());

            if ($quoteApprovalResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage($translatedMessage);

                continue;
            }

            $this->addErrorMessage($translatedMessage);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer
     *
     * @return void
     */
    protected function updateSessionQuoteOnSuccessfulResponse(
        QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer
    ): void {
        if (!$quoteApprovalResponseTransfer->getIsSuccessful()) {
            return;
        }

        $this->getFactory()
            ->getQuoteClient()
            ->setQuote($quoteApprovalResponseTransfer->getQuote());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToReferer(Request $request): RedirectResponse
    {
        $referer = $request->headers->get(static::PARAM_REFERER);

        return $this->redirectResponseExternal($referer);
    }
}
