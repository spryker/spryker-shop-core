<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartToShoppingListWidget\Controller;

use SprykerShop\Yves\CartToShoppingListWidget\CartToShoppingListWidgetConfig;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartToShoppingListWidget\CartToShoppingListWidgetFactory getFactory()
 */
class CartToShoppingListController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request)
    {
        $cartToShoppingListForm = $this->getFactory()->getCartFromShoppingListForm(null)->handleRequest($request);

        if ($cartToShoppingListForm->isSubmitted() && $cartToShoppingListForm->isValid()) {
            $shoppingListFromCartRequest = $cartToShoppingListForm->getData();
            $shoppingListFromCartRequest->setCustomer($this->getFactory()->getCustomerClient()->getCustomer());

            $shoppingListTransfer = $this->getFactory()->getShoppingListClient()->createShoppingListFromQuote($shoppingListFromCartRequest);

            if ($shoppingListTransfer) {
                $this->addSuccessMessage('customer.account.shopping_list.create.success');
                return $this->redirectResponseInternal(CartToShoppingListWidgetConfig::SHOPPING_LIST_REDIRECT_URL);
            }
        }

        $this->addErrorMessage('customer.account.shopping_list.create.failed');
        return $this->redirectResponseInternal(CartToShoppingListWidgetConfig::CART_REDIRECT_URL);
    }
}