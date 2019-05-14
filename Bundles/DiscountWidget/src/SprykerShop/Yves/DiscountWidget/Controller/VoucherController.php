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
 * @deprecated Use CartCode and CartCodeWidget modules instead.
 *
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
            $voucherCode = $form->get(CartVoucherForm::FIELD_VOUCHER_CODE)->getData();

            $this->getFactory()
                ->createVoucherHandler()
                ->add($voucherCode);
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
        $voucherCode = $request->query->get('code');
        if (!empty($voucherCode)) {
            $this->getFactory()
                ->createVoucherHandler()
                ->remove($voucherCode);
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
