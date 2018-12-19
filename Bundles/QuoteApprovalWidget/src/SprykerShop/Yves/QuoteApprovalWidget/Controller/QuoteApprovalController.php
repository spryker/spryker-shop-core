<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Controller;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Spryker\Client\Customer\CustomerClient;
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
        //todo: add check that customer can do this;

        $customer = (new CustomerClient())->getCustomer();

        $quoteApprovalRequestTransfer = (new QuoteApprovalRequestTransfer())
            ->setIdQuoteApproval($idQuoteApproval)
            ->setFkCompanyUser($customer->getCompanyUserTransfer()->getIdCompanyUser());

        $quoteApprovalResponseTransfer = $this->getFactory()
            ->getQuoteApprovalClient()
            ->approveQuote($quoteApprovalRequestTransfer);

        if (!$quoteApprovalResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage('Can not update');//todo: translation

            return $this->redirectBack($request);
        }

        $this->addSuccessMessage('Updated');//todo: translation

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
        //todo: implement

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
