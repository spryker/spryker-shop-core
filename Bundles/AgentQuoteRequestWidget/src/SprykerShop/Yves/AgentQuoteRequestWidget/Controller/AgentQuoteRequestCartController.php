<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget\Controller;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\AgentQuoteRequestWidget\Form\AgentQuoteRequestCartForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestWidget\AgentQuoteRequestWidgetFactory getFactory()
 */
class AgentQuoteRequestCartController extends AbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_UPDATED = 'quote_request_page.quote_request.updated';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    protected const ROUTE_CART = 'cart';

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider\AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_EDIT
     */
    protected const ROUTE_AGENT_QUOTE_REQUEST_EDIT = 'agent/quote-request/edit';

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider\AgentQuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $agentQuoteRequestCartForm = $this->getFactory()
            ->getAgentQuoteRequestCartForm()
            ->handleRequest($request);

        if ($agentQuoteRequestCartForm->isSubmitted()) {
            $quoteRequestResponseTransfer = $this->getFactory()
                ->createAgentQuoteRequestCartHandler()
                ->updateQuoteRequest();

            if ($quoteRequestResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_UPDATED);
            }

            $this->handleResponseErrors($quoteRequestResponseTransfer);

            if ($request->get(AgentQuoteRequestCartForm::SUBMIT_BUTTON_SAVE_AND_BACK) !== null) {
                $this->reloadQuoteForCustomer($quoteRequestResponseTransfer->getQuoteRequest());

                return $this->redirectResponseInternal(static::ROUTE_AGENT_QUOTE_REQUEST_EDIT, [
                    static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestResponseTransfer->getQuoteRequest()->getQuoteRequestReference(),
                ]);
            }
        }

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(QuoteRequestResponseTransfer $quoteRequestResponseTransfer): void
    {
        foreach ($quoteRequestResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return void
     */
    protected function reloadQuoteForCustomer(QuoteRequestTransfer $quoteRequestTransfer): void
    {
        $this->getFactory()
            ->getPersistentCartClient()
            ->reloadQuoteForCustomer($quoteRequestTransfer->getCompanyUser()->getCustomer());
    }
}
