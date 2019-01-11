<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Controller;

use Generated\Shared\Transfer\QuoteApprovalCancelRequestTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory
 */
class QuoteApproveRequestController extends AbstractController
{
    protected const ROUTE_CART = 'cart';

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
            $$this->getFactory()->getQuoteApprovalClient()->sendApproveRequest($quoteApproveRequestForm->getData());
        }

        return $this->redirectResponseInternal(static::ROUTE_CART);
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

        $this->getFactory()
            ->getQuoteApprovalClient()
            ->cancelApprovalRequest($quoteApprovalCancelRequestTransfer);

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }
}
