<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestTransfer;
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
        $response = $this->executeCreateAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@QuoteRequestPage/views/quote-request/create-quote-request.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCreateAction(Request $request)
    {
        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser($companyUserTransfer);

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
