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
class QuoteRequestEditController extends QuoteRequestAbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_SAVED = 'quote_request_page.revise.quote_request.saved';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST_DETAILS
     */
    protected const ROUTE_QUOTE_REQUEST_DETAILS = 'quote-request/details';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    /**
     * @param string $quoteRequestReference
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(string $quoteRequestReference, Request $request)
    {
        $response = $this->executeIndexAction($quoteRequestReference, $request);

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
    protected function executeIndexAction(string $quoteRequestReference, Request $request)
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
                $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_SAVED);

                return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_DETAILS, [
                    static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
                ]);
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
