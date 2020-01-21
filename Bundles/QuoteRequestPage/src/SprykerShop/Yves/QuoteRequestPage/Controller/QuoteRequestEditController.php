<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use SprykerShop\Yves\QuoteRequestPage\Form\QuoteRequestForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestEditController extends QuoteRequestAbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_UPDATED = 'quote_request_page.quote_request.updated';
    protected const GLOSSARY_KEY_QUOTE_REQUEST_SAVED = 'quote_request_page.revise.quote_request.saved';
    protected const GLOSSARY_KEY_QUOTE_REQUEST_SENT_TO_USER = 'quote_request_page.quote_request.sent_to_user';
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @param string $quoteRequestReference
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
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
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(string $quoteRequestReference, Request $request)
    {
        $quoteRequestClient = $this->getFactory()->getQuoteRequestClient();
        $quoteRequestTransfer = $this->getCompanyUserQuoteRequestByReference($quoteRequestReference);

        if (!$quoteRequestClient->isQuoteRequestEditable($quoteRequestTransfer)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS);

            return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_DETAILS, [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
            ]);
        }

        $quoteRequestForm = $this->getFactory()
            ->getQuoteRequestForm($quoteRequestTransfer)
            ->handleRequest($request);

        if ($quoteRequestForm->isSubmitted() && $quoteRequestForm->isValid()) {
            return $this->processQuoteRequestForm($quoteRequestForm, $request);
        }

        $shipmentGroupTransfers = $this->getFactory()
            ->createShipmentGrouper()
            ->groupItemsByShippingAddress($quoteRequestTransfer);

        return [
            'quoteRequestForm' => $quoteRequestForm->createView(),
            'shipmentGroups' => $shipmentGroupTransfers,
            'totalShippingCosts' => $this->getFactory()->createShipmentCostExtractor()->extractTotalShipmentCosts($shipmentGroupTransfers),
        ];
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendToUserAction(string $quoteRequestReference): RedirectResponse
    {
        $response = $this->executeSendToUserAction($quoteRequestReference);

        return $response;
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeSendToUserAction(string $quoteRequestReference): RedirectResponse
    {
        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());

        $quoteRequestResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->sendQuoteRequestToUser($quoteRequestFilterTransfer);

        if ($quoteRequestResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_SENT_TO_USER);
        }

        $this->handleResponseErrors($quoteRequestResponseTransfer);

        return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_DETAILS, [
            static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $quoteRequestForm
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processQuoteRequestForm(FormInterface $quoteRequestForm, Request $request): RedirectResponse
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer */
        $quoteRequestTransfer = $quoteRequestForm->getData();

        $quoteRequestResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->updateQuoteRequest($quoteRequestTransfer);

        if ($quoteRequestResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_UPDATED);
        }

        $this->handleResponseErrors($quoteRequestResponseTransfer);

        if ($request->get(QuoteRequestForm::SUBMIT_BUTTON_SEND_TO_USER) !== null && $quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_SEND_TO_USER, [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestTransfer->getQuoteRequestReference(),
            ]);
        }

        return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_EDIT, [
            static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestTransfer->getQuoteRequestReference(),
        ]);
    }
}
