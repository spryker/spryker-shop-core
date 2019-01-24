<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestCreateController extends QuoteRequestAbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_CREATED_SUCCESS = 'quote_request_page.quote_request.created';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $response = $this->executeCreateAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@QuoteRequestPage/views/create-quote-request/create-quote-request.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCreateAction(Request $request)
    {
        $quoteRequestForm = $this->getFactory()
            ->getQuoteRequestForm()
            ->handleRequest($request);

        if ($quoteRequestForm->isSubmitted() && $quoteRequestForm->isValid()) {
            $quoteRequestResponseTransfer = $this->getFactory()
                ->createQuoteRequestHandler()
                ->createQuoteRequest($quoteRequestForm->getData());

            if ($quoteRequestResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_CREATED_SUCCESS);

                return $this->redirectResponseInternal(QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST);
            }
        }

        $quoteTransfer = $this->getFactory()->getCartClient()->getQuote();
        $cartItems = $quoteTransfer->getItems()->getArrayCopy();

        return [
            'quoteRequestForm' => $quoteRequestForm->createView(),
            'cart' => $quoteTransfer,
            'cartItems' => $cartItems,
        ];
    }
}
