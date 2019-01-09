<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Controller;

use Generated\Shared\Transfer\QuoteApprovalCancelRequestTransfer;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory
 */
class QuoteApproveRequestController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendQuoteApproveRequestAction(Request $request): RedirectResponse
    {
        $quoteApproveRequestForm = $this->getFactory()
            ->createQuoteApproveRequestForm(
                $this->getFactory()->getQuoteClient()->getQuote(),
                $this->getLocale()
            );

        $quoteApproveRequestForm->handleRequest($request);

        if ($quoteApproveRequestForm->isSubmitted() && $quoteApproveRequestForm->isValid()) {
            $quoteResponseTransfer = $this->getFactory()->getQuoteApprovalClient()->sendApproveRequest($quoteApproveRequestForm->getData());

            if ($quoteResponseTransfer->getIsSuccessful()) {
                $this->getFactory()->getQuoteClient()->setQuote($quoteResponseTransfer->getQuoteTransfer());
            }
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param int $idQuoteApproval
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cancelQuoteApprovalRequestAction(int $idQuoteApproval): RedirectResponse
    {
        $quoteApprovalCancelRequestTransfer = new QuoteApprovalCancelRequestTransfer();
        $quoteApprovalCancelRequestTransfer->setIdQuoteApproval($idQuoteApproval)
            ->setQuote($this->getFactory()->getQuoteClient()->getQuote())
            ->setCustomer($this->getFactory()->getCustomerClient()->getCustomer());

        $quoteResponseTransfer = $this->getFactory()
            ->getQuoteApprovalClient()
            ->cancelApprovalRequest($quoteApprovalCancelRequestTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->getFactory()->getQuoteClient()->setQuote($quoteResponseTransfer->getQuoteTransfer());
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }
}
