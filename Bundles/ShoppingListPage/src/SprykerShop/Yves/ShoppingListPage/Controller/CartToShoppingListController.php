<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Controller;

use SprykerShop\Yves\CartToShoppingListWidget\CartToShoppingListWidgetConfig;
use SprykerShop\Yves\ShoppingListPage\Form\ShoppingListFromCartForm;
use Symfony\Component\HttpFoundation\Request;

class CartToShoppingListController extends AbstractShoppingListController
{
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
        $cartToShoppingListForm = $this->getFactory()
            ->getCartFromShoppingListForm($idQuote)
            ->handleRequest($request);

        if ($cartToShoppingListForm->isSubmitted() && $cartToShoppingListForm->isValid()) {
            $shoppingListFromCartRequest = $cartToShoppingListForm->getData();
            if (!$shoppingListFromCartRequest->getShoppingListName()) {
                $shoppingListFromCartRequest->setShoppingListName(
                    $cartToShoppingListForm->get(ShoppingListFromCartForm::FIELD_NEW_SHOPPING_LIST_NAME_INPUT)->getData()
                );
            }
            $shoppingListFromCartRequest->setCustomer($this->getFactory()->getCustomerClient()->getCustomer());

            $shoppingListTransfer = $this->getFactory()->getShoppingListClient()->createShoppingListFromQuote($shoppingListFromCartRequest);

            $this->addSuccessMessage(static::GLOSSARY_KEY_SHOPPING_LIST_CART_ITEMS_ADD_SUCCESS);

            return $this->redirectResponseInternal(CartToShoppingListWidgetConfig::SHOPPING_LIST_REDIRECT_URL, [
                'idShoppingList' => $shoppingListTransfer->getIdShoppingList(),
            ]);
        }

        $cart = $this->getFactory()->getMultiCartClient()->findQuoteById($idQuote);

        return [
            'cartToShoppingListForm' => $cartToShoppingListForm->createView(),
            'cart' => $cart,
        ];
    }
}
