<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class CreateController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $response = $this->action($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@QuoteRequestPage/views/quote-request/create-quote-request.twig');
    }

    protected function action(Request $request)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $cartItems = $quoteTransfer->getItems()->getArrayCopy();

        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        $quoteRequestForm = $this->getFactory()
            ->getQuoteRequestForm((new QuoteRequestTransfer())->setCompanyUser($companyUserTransfer))
            ->handleRequest($request);

        return [
            'quoteRequestForm' => $quoteRequestForm->createView(),
            'cart' => $quoteTransfer,
            'cartItems' => $cartItems,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCreateAction(Request $request)
    {
        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->setQuote($quoteTransfer);

        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setLatestVersion($quoteRequestVersionTransfer)
            ->setMetadata(['one' => 'one', 'two' => 'two']);

        $quoteRequestResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->create($quoteRequestTransfer);

        dump($quoteRequestResponseTransfer);die;

        $quoteRequestForm = $this->getFactory()
            ->getQuoteRequestForm($quoteRequestTransfer)
            ->handleRequest($request);

        dump($quoteRequestForm);die;

        if ($quoteRequestForm->isSubmitted() && $quoteRequestForm->isValid()) {
//            $shoppingListTransfer = $this->getFactory()
//                ->createCreateFromCartHandler()
//                ->createShoppingListFromCart($cartToShoppingListForm);
//
//            $this->addSuccessMessage(static::GLOSSARY_KEY_SHOPPING_LIST_CART_ITEMS_ADD_SUCCESS);
//            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_DETAILS, [
//                'idShoppingList' => $shoppingListTransfer->getIdShoppingList(),
//            ]);
        }

        return [
//            'cartToShoppingListForm' => $cartToShoppingListForm->createView(),
//            'cart' => $cart,
        ];
    }
}
