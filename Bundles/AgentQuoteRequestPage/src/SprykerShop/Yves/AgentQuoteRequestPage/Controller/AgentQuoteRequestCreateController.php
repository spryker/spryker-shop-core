<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Controller;

use SprykerShop\Yves\AgentQuoteRequestPage\Form\AgentQuoteRequestCreateForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageFactory getFactory()
 */
class AgentQuoteRequestCreateController extends AgentQuoteRequestAbstractController
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

        return $this->view($response, [], '@AgentQuoteRequestPage/views/quote-request-create/quote-request-create.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCreateAction(Request $request)
    {
        $quoteRequestCreateForm = $this->getFactory()
            ->getAgentQuoteRequestCreateForm()
            ->handleRequest($request);

        if ($quoteRequestCreateForm->isSubmitted()) {
            $quoteRequestResponseTranasfer = $this->getFactory()
                ->createAgentQuoteRequestCreateHandler()
                ->createQuoteRequest((int)$request->get(AgentQuoteRequestCreateForm::FILED_ID_COMPANY_USER));

            $this->handleResponseErrors($quoteRequestResponseTranasfer);

            if ($quoteRequestResponseTranasfer->getIsSuccessful()) {
                $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_CREATED);

                return $this->redirectResponseInternal(static::ROUTE_AGENT_QUOTE_REQUEST_EDIT, [
                    static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestResponseTranasfer->getQuoteRequest()->getQuoteRequestReference(),
                ]);
            }
        }

        return [
            'quoteRequestCreateForm' => $quoteRequestCreateForm->createView(),
        ];
    }
}
