<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestEditAddressController extends QuoteRequestAbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_CONVERTED_TO_CART = 'quote_request_page.quote_request.converted_to_cart';

    /**
     * @param string $quoteRequestReference
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(string $quoteRequestReference)
    {
        $response = $this->executeIndexAction($quoteRequestReference);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request, string $quoteRequestReference)
    {
        $response = $this->executeConfirmAction($request, $quoteRequestReference);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@QuoteRequestPage/views/quote-request-edit-address-confirm/quote-request-edit-address-confirm.twig');
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(string $quoteRequestReference): RedirectResponse
    {
        $quoteTransfer = $this->getFactory()->getCartClient()->getQuote();

        if ($quoteTransfer->getQuoteRequestReference()
            && $quoteTransfer->getQuoteRequestReference() !== $quoteRequestReference) {
            return $this->redirectResponseInternal(
                static::ROUTE_QUOTE_REQUEST_EDIT_ADDRESS_CONFIRM,
                [static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference]
            );
        }

        $quoteRequestTransfer = $this->getCompanyUserQuoteRequestByReference($quoteRequestReference);

        return $this->convertQuoteRequestToQuote($quoteRequestTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeConfirmAction(Request $request, string $quoteRequestReference)
    {
        $quoteTransfer = $this->getFactory()->getCartClient()->getQuote();

        if ($quoteTransfer->getQuoteRequestReference() === $quoteRequestReference) {
            return $this->redirectResponseInternal(
                static::ROUTE_QUOTE_REQUEST_EDIT_ADDRESS,
                [static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference]
            );
        }

        $quoteRequestTransfer = $this->getCompanyUserQuoteRequestByReference($quoteRequestReference);

        $quoteRequestEditAddressConfirmForm = $this->getFactory()
            ->getQuoteRequestEditAddressConfirmForm($quoteRequestTransfer)
            ->handleRequest($request);

        if ($quoteRequestEditAddressConfirmForm->isSubmitted()) {
            return $this->convertQuoteRequestToQuote($quoteRequestEditAddressConfirmForm->getData());
        }

        return [
            'quoteRequestEditAddressConfirmForm' => $quoteRequestEditAddressConfirmForm->createView(),
            'quoteRequestReference' => $quoteTransfer->getQuoteRequestReference(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function convertQuoteRequestToQuote(QuoteRequestTransfer $quoteRequestTransfer): RedirectResponse
    {
        $quoteResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->convertQuoteRequestToQuote($quoteRequestTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_CONVERTED_TO_CART);
        }

        $this->handleQuoteResponseErrors($quoteResponseTransfer);

        return $this->redirectResponseInternal(static::ROUTE_CHECKOUT_ADDRESS);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return void
     */
    protected function handleQuoteResponseErrors(QuoteResponseTransfer $quoteResponseTransfer): void
    {
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $this->addErrorMessage($quoteErrorTransfer->getMessage());
        }
    }
}
