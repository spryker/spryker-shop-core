<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Controller;

use SprykerShop\Yves\ShoppingListPage\Plugin\Provider\ShoppingListPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CartToShoppingListController extends AbstractShoppingListController
{
    protected const PARAM_REFERER = 'referer';
    protected const GLOSSARY_KEY_CART_NOT_AVAILABLE = 'shopping_list.cart.not_available';
    protected const GLOSSARY_KEY_SHOPPING_LIST_CART_ITEMS_ADD_SUCCESS = 'shopping_list.cart.items_add.success';
    protected const GLOSSARY_KEY_SHOPPING_LIST_CART_ITEMS_ADD_FAILED = 'shopping_list.cart.items_add.failed';

    /**
     * @param int $idQuote
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createFromCartAction(int $idQuote, Request $request)
    {
        $response = $this->executeCreateFromCartAction($idQuote, $request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@ShoppingListPage/views/cart-to-shopping-list/create-from-cart.twig');
    }

    /**
     * @param int $idQuote
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCreateFromCartAction(int $idQuote, Request $request)
    {
        $quoteTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->findQuoteById($idQuote);

        if (!$quoteTransfer) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CART_NOT_AVAILABLE);

            return $this->redirectToReferer($request);
        }

        $cartToShoppingListForm = $this->getFactory()
            ->getCartFromShoppingListForm($idQuote)
            ->handleRequest($request);

        if ($cartToShoppingListForm->isSubmitted() && $cartToShoppingListForm->isValid()) {
            $shoppingListTransfer = $this->getFactory()
                ->createCreateFromCartHandler()
                ->createShoppingListFromCart($cartToShoppingListForm);

            $this->addSuccessMessage(static::GLOSSARY_KEY_SHOPPING_LIST_CART_ITEMS_ADD_SUCCESS);

            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_DETAILS, [
                'idShoppingList' => $shoppingListTransfer->getIdShoppingList(),
            ]);
        }

        return [
            'cartToShoppingListForm' => $cartToShoppingListForm->createView(),
            'cart' => $quoteTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToReferer(Request $request): RedirectResponse
    {
        $referer = $request->headers->get(static::PARAM_REFERER);

        return $this->redirectResponseExternal($referer);
    }
}
