<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Controller;

use Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory
 */
class QuoteApprovalController extends AbstractController
{
    protected const ROUTE_CART = 'cart';

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
            $quoteApprovalResponseTransfer = $this->getFactory()->getQuoteApprovalClient()->createQuoteApproval($quoteApproveRequestForm->getData());

            $this->addMessagesFromQuoteApprovalResponse($quoteApprovalResponseTransfer);
        }

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }

    /**
     * @param int $idQuoteApproval
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeQuoteApprovalAction(int $idQuoteApproval): RedirectResponse
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer) {
            $quoteApprovalRemoveRequestTransfer = new QuoteApprovalRemoveRequestTransfer();

            $quoteApprovalRemoveRequestTransfer->setIdQuoteApproval($idQuoteApproval)
                ->setCustomerReference($customerTransfer->getCustomerReference());

            $quoteApprovalResponseTransfer = $this->getFactory()
                ->getQuoteApprovalClient()
                ->removeQuoteApproval($quoteApprovalRemoveRequestTransfer);

            $this->addMessagesFromQuoteApprovalResponse($quoteApprovalResponseTransfer);
        }

        return $this->redirectResponseInternal(static::ROUTE_CART);
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
    protected function addMessagesFromQuoteApprovalResponse(QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer)
    {
        $messageTransfer = $quoteApprovalResponseTransfer->getMessage();

        if ($quoteApprovalResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage($this->getTranslatedMessage(
                $messageTransfer->getValue(),
                $messageTransfer->getParameters()
            ));

            return;
        }

        $this->addErrorMessage(
            $this->getTranslatedMessage(
                $messageTransfer->getValue(),
                $messageTransfer->getParameters()
            )
        );
    }
}
