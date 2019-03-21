<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestReviseController extends QuoteRequestAbstractController
{
    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST_DETAILS
     */
    protected const ROUTE_QUOTE_REQUEST_DETAILS = 'quote-request/details';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    public const ROUTE_CART = 'cart';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request, string $quoteRequestReference): RedirectResponse
    {
        $this->getFactory()
            ->getQuoteRequestClient()
            ->markQuoteRequestAsDraft((new QuoteRequestCriteriaTransfer())->setQuoteRequestReference($quoteRequestReference));

        return $this->redirectToReferer($request);
    }

    /**
     * @param string $quoteRequestReference
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(string $quoteRequestReference, Request $request)
    {
        $response = $this->executeEditAction($quoteRequestReference, $request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@QuoteRequestPage/views/quote-request-edit/quote-request-edit.twig');
    }

    /**
     * @param string $quoteRequestReference
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeEditAction(string $quoteRequestReference, Request $request)
    {
        $quoteRequestTransfer = $this->getCompanyUserQuoteRequestByReference($quoteRequestReference);

        $quoteRequestForm = $this->getFactory()
            ->getQuoteRequestForm($quoteRequestTransfer)
            ->handleRequest($request);

        if ($quoteRequestForm->isSubmitted() && $quoteRequestForm->isValid()) {
            $quoteRequestResponseTransfer = $this->getFactory()
                ->createQuoteRequestHandler()
                ->updateQuoteRequest($quoteRequestForm->getData());

            if ($quoteRequestResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage('quote_request_page.revise.quote_request.saved');

                return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_DETAILS, [static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference]);
            }

            $this->handleResponseErrors($quoteRequestResponseTransfer);
        }

        return [
            'quoteRequestForm' => $quoteRequestForm->createView(),
        ];
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editItemsAction(string $quoteRequestReference): RedirectResponse
    {
        $quoteRequestTransfer = $this->getCompanyUserQuoteRequestByReference($quoteRequestReference);

        $this->getFactory()
            ->getQuoteRequestClient()
            ->convertQuoteRequestToEditableQuote($quoteRequestTransfer);

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function submitAction(string $quoteRequestReference): RedirectResponse
    {
        $this->getFactory()
            ->getQuoteRequestClient()
            ->markQuoteRequestAsWaiting(
                (new QuoteRequestCriteriaTransfer())->setQuoteRequestReference($quoteRequestReference)
            );

        return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_DETAILS, [static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference]);
    }
}
