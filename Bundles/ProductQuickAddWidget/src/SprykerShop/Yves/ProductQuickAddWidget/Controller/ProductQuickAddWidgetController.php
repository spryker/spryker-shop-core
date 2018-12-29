<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuickAddWidget\Controller;

use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\ProductQuickAddWidget\Form\ProductQuickAddForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductQuickAddWidget\CartPageFactory getFactory()
 */
class ProductQuickAddWidgetController extends AbstractController
{
    protected const MESSAGE_QUICK_ADD_TO_CART_INCORRECT_INPUT_DATA = 'cart.quick_add_to_cart.incorrect_input_data';

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $form = $this->getFactory()
            ->getProductQuickAddForm()
            ->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_QUICK_ADD_TO_CART_INCORRECT_INPUT_DATA);

            return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        }
        $redirectRouteName = $form->getData()[ProductQuickAddForm::FIELD_REDIRECT_ROUTE_NAME];

        return $this->redirectResponseInternal($redirectRouteName, $request->request->all());
    }
}
