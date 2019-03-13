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
                ->createQuoteRequest($request->query->get(AgentQuoteRequestCreateForm::FILED_ID_COMPANY_USER));
        }

        return [
            'quoteRequestCreateForm' => $quoteRequestCreateForm->createView(),
        ];
    }
}
