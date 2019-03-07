<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use SprykerShop\Yves\AgentQuoteRequestPage\Form\AgentQuoteRequestForm;
use SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider\AgentQuoteRequestPageControllerProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageFactory getFactory()
 */
class AgentQuoteRequestEditController extends AgentQuoteRequestAbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_UPDATED = 'quote_request_page.quote_request.updated';
    protected const GLOSSARY_KEY_QUOTE_REQUEST_SENT_TO_CUSTOMER = 'quote_request_page.quote_request.sent_to_customer';
    protected const ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function startEditAction(string $quoteRequestReference): RedirectResponse
    {
        $response = $this->executeStartEditAction($quoteRequestReference);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, string $quoteRequestReference)
    {
        $response = $this->executeEditAction($request, $quoteRequestReference);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@AgentQuoteRequestPage/views/quote-request-edit/quote-request-edit.twig');
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendToCustomerAction(string $quoteRequestReference): RedirectResponse
    {
        $response = $this->executeSendToCustomerAction($quoteRequestReference);

        return $response;
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeStartEditAction(string $quoteRequestReference): RedirectResponse
    {
        $quoteRequestResponseTransfer = $this->getFactory()
            ->getAgentQuoteRequestClient()
            ->markQuoteRequestInProgress((new QuoteRequestCriteriaTransfer())->setQuoteRequestReference($quoteRequestReference));

        $this->handleResponseErrors($quoteRequestResponseTransfer);

        return $this->redirectResponseInternal(AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_EDIT, [
            AgentQuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
        ]);
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeSendToCustomerAction(string $quoteRequestReference): RedirectResponse
    {
        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setWithHidden(true)
            ->setQuoteRequestReference($quoteRequestReference);

        $quoteRequestResponseTransfer = $this->getFactory()
            ->getAgentQuoteRequestClient()
            ->sendQuoteRequestToCustomer($quoteRequestCriteriaTransfer);

        if ($quoteRequestResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_SENT_TO_CUSTOMER);
        }

        $this->handleResponseErrors($quoteRequestResponseTransfer);

        return $this->redirectResponseInternal(AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_DETAILS, [
            AgentQuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeEditAction(Request $request, string $quoteRequestReference)
    {
        $agentQuoteRequestClient = $this->getFactory()->getAgentQuoteRequestClient();
        $quoteRequestForm = $this->getFactory()->getAgentQuoteRequestForm($quoteRequestReference);
        /** @var \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer */
        $quoteRequestTransfer = $quoteRequestForm->getData();

        if ($agentQuoteRequestClient->isQuoteRequestCanStartEditable($quoteRequestTransfer)) {
            return $this->redirectResponseInternal(AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_START_EDIT, [
                AgentQuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
            ]);
        }

        if (!$agentQuoteRequestClient->isQuoteRequestEditable($quoteRequestTransfer)) {
            $this->addErrorMessage(static::ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS);

            return $this->redirectResponseInternal(AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_DETAILS, [
                AgentQuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
            ]);
        }

        $quoteRequestForm->handleRequest($request);

        if ($quoteRequestForm->isSubmitted() && $quoteRequestForm->isValid()) {
            return $this->processAgentQuoteRequestForm($quoteRequestForm, $request);
        }

        return [
            'quoteRequestForm' => $quoteRequestForm->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $quoteRequestForm
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processAgentQuoteRequestForm(FormInterface $quoteRequestForm, Request $request): RedirectResponse
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer */
        $quoteRequestTransfer = $quoteRequestForm->getData();

        $quoteRequestResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->updateQuoteRequest($quoteRequestTransfer);

        if ($quoteRequestResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_UPDATED);
        }

        $this->handleResponseErrors($quoteRequestResponseTransfer);

        if ($request->get(AgentQuoteRequestForm::SUBMIT_BUTTON_SEND_TO_CUSTOMER) !== null && $quoteRequestResponseTransfer->getIsSuccess()) {
            return $this->redirectResponseInternal(AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_SEND_TO_CUSTOMER, [
                AgentQuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestTransfer->getQuoteRequestReference(),
            ]);
        }

        return $this->redirectResponseInternal(AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_EDIT, [
            AgentQuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestTransfer->getQuoteRequestReference(),
        ]);
    }
}
