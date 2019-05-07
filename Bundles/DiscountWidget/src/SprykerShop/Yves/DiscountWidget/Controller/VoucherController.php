<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Controller;

use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\DiscountWidget\Form\CartVoucherForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\DiscountWidget\DiscountWidgetFactory getFactory()
 */
class VoucherController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()
            ->getCartVoucherForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $code = $form->get(CartVoucherForm::FIELD_VOUCHER_CODE)->getData();

            $this->getFactory()
                ->createVoucherHandler()
                ->add($code);
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Request $request)
    {
        $code = $request->query->get('code');
        if (!empty($code)) {
            $this->getFactory()
                ->createVoucherHandler()
                ->remove($code);
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearAction()
    {
        $this->getFactory()->createVoucherHandler()->clear();

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }
}
