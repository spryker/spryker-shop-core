<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestEditItemsController extends QuoteRequestAbstractController
{
    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    protected const ROUTE_CART = 'cart';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request, string $quoteRequestReference)
    {
        $response = $this->executeIndexAction($request, $quoteRequestReference);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@QuoteRequestPage/views/quote-request-edit-items-confirm/quote-request-edit-items-confirm.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeIndexAction(Request $request, string $quoteRequestReference)
    {
        $quoteTransfer = $this->getFactory()->getCartClient()->getQuote();
        $quoteRequestTransfer = $this->getCompanyUserQuoteRequestByReference($quoteRequestReference);
        $quoteRequestEditItemsConfirmForm = $this->getFactory()->getQuoteRequestEditItemsConfirmForm($quoteRequestTransfer);

        $quoteRequestEditItemsConfirmForm->handleRequest($request);

        if ($quoteTransfer->getQuoteRequestReference() === $quoteRequestReference
            || $quoteRequestEditItemsConfirmForm->isSubmitted()) {
            $this->getFactory()
                ->getQuoteRequestClient()
                ->convertQuoteRequestToQuote($quoteRequestTransfer);

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        return [
            'quoteRequestEditItemsConfirmForm' => $quoteRequestEditItemsConfirmForm->createView(),
            'quoteRequestReference' => $quoteRequestReference,
        ];
    }
}
