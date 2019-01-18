<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class CreateController extends AbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_CREATED_SUCCESS = 'customer.account.shopping_list.delete.success';

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        if ($companyUserTransfer === null) {
            throw new NotFoundHttpException("Only company users are allowed to access this page");
        }
    }

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
                $this->addErrorMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_CREATED_SUCCESS);

                return $this->redirectResponseInternal(QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST);
            }
        }

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $cartItems = $quoteTransfer->getItems()->getArrayCopy();

        // TODO: check quoteId if exists?

        return [
            'quoteRequestForm' => $quoteRequestForm->createView(),
            'cart' => $quoteTransfer,
            'cartItems' => $cartItems,
        ];
    }
}
