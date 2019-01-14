<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestTransfer;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestController extends AbstractQuoteRequestController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_FOUND = 'quote_request.not_found';

    /**
     * @param string $quoteRequestReference
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(string $quoteRequestReference)
    {
        $response = $this->executeIndexAction($quoteRequestReference);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            [],
            '@QuoteRequestPage/views/quote-request/quote-request.twig'
        );
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(string $quoteRequestReference)
    {
        $customerTransfer = $this->getCustomer();

        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setCompanyUser($customerTransfer->getCompanyUserTransfer());

        $quoteRequestOverviewResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->findQuoteRequestByReference($quoteRequestTransfer);

        if ($quoteRequestOverviewResponseTransfer->getIsSuccess() !== true) {
            $errorMessages = $this->getFactory()->getZedRequestClient()->getLastResponseErrorMessages();
            foreach ($errorMessages as $errorMessageTransfer) {
                $this->addErrorMessage($errorMessageTransfer->getValue());
            }

            return $this->redirectResponseInternal(QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST);
        }

        $quoteRequestItems = $this->getQuoteRequestItems($quoteRequestOverviewResponseTransfer);

        return [
            'quoteRequestItems' => $quoteRequestItems,
            'quoteRequestOverview' => $quoteRequestOverviewResponseTransfer,
        ];
    }
}
