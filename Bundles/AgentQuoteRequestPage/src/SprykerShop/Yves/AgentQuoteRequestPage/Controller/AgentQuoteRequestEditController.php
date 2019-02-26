<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageConfig;
use SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider\AgentQuoteRequestPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageFactory getFactory()
 */
class AgentQuoteRequestEditController extends AgentQuoteRequestAbstractController
{
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
    protected function executeStartEditAction(string $quoteRequestReference): RedirectResponse
    {
        $quoteRequestResponseTransfer = $this->getFactory()->getAgentQuoteRequestClient()
            ->setQuoteRequestEditable((new QuoteRequestFilterTransfer())->setQuoteRequestReference($quoteRequestReference));

        if (!$quoteRequestResponseTransfer->getIsSuccess()) {
            $this->handleResponseErrors($quoteRequestResponseTransfer);
        }

        return $this->redirectResponseInternal(AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_EDIT, [
            static::ROUTE_PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
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
        $quoteRequestForm = $this->getFactory()->getAgentQuoteRequestForm($quoteRequestReference);

        /** @var \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer */
        $quoteRequestTransfer = $quoteRequestForm->getData();

        if ($quoteRequestTransfer->getStatus() !== AgentQuoteRequestPageConfig::STATUS_IN_PROGRESS) {
            return $this->redirectResponseInternal(AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_START_EDIT, [
                static::ROUTE_PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
            ]);
        }

//        if ($quoteRequestForm->isSubmitted() && $quoteRequestForm->isValid()) {
//            $quoteRequestResponseTransfer = $this->getFactory()
//                ->getShoppingListClient()
//                ->updateShoppingList($quoteRequestForm->getData());
//
//            if ($quoteRequestResponseTransfer->getIsSuccess()) {
//                $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_UPDATED);
//
//                return $this->redirectResponseInternal(AgentQuoteRequestPageControllerProvider::ROUTE_SHOPPING_LIST_UPDATE, [
//                    static::ROUTE_PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
//                ]);
//            }
//
//            $this->handleResponseErrors($quoteRequestResponseTransfer);
//        }

        return [
            'quoteRequestForm' => $quoteRequestForm->createView(),
        ];
    }
}
