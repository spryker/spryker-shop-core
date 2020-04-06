<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestCreateController extends QuoteRequestAbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_CREATED = 'quote_request_page.quote_request.created';

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

        return $this->view($response, [], '@QuoteRequestPage/views/quote-request-create/quote-request-create.twig');
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
            return $this->processQuoteRequestForm($quoteRequestForm);
        }

        return $this->getViewParameters($quoteRequestForm);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $quoteRequestForm
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processQuoteRequestForm(FormInterface $quoteRequestForm)
    {
        $quoteRequestResponseTransfer = $this->getFactory()
            ->createQuoteRequestHandler()
            ->createQuoteRequest($quoteRequestForm->getData());

        if ($quoteRequestResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_CREATED);

            return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_DETAILS, [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestResponseTransfer->getQuoteRequest()->getQuoteRequestReference(),
            ]);
        }

        $this->handleResponseErrors($quoteRequestResponseTransfer);

        return $this->getViewParameters($quoteRequestForm);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $quoteRequestForm
     *
     * @return array
     */
    protected function getViewParameters(FormInterface $quoteRequestForm): array
    {
        $quoteRequestTransfer = $quoteRequestForm->getData();

        $shipmentGroupTransfers = $this->getFactory()
            ->createShipmentGrouper()
            ->groupItemsByShippingAddress($quoteRequestTransfer);

        $itemExtractor = $this->getFactory()->createItemExtractor();

        return [
            'quoteRequestForm' => $quoteRequestForm->createView(),
            'shipmentGroups' => $shipmentGroupTransfers,
            'itemsWithShipment' => $itemExtractor->extractItemsWithShipment($quoteRequestTransfer),
            'itemsWithoutShipment' => $itemExtractor->extractItemsWithoutShipment($quoteRequestTransfer),
            'shipmentExpenses' => $this->getFactory()->createExpenseExtractor()->extractShipmentExpenses($quoteRequestTransfer),
        ];
    }
}
