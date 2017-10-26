<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\DiscountWidget\Controller;

use Pyz\Yves\Application\Controller\AbstractController;
use SprykerShop\Yves\DiscountWidget\Form\VoucherForm;
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
            ->getVoucherForm()
            ->handleRequest($request);

        if ($form->isValid()) {
            $voucherCode = $form->get(VoucherForm::FIELD_VOUCHER_CODE)->getData();

            $this->getFactory()
                ->createCartVoucherHandler()
                ->add($voucherCode);
        }

//        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        return $this->redirectResponseInternal('cart');
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
                ->createCartVoucherHandler()
                ->remove($voucherCode);
        }

//        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        return $this->redirectResponseInternal('cart');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearAction()
    {
        $this->getFactory()->createCartVoucherHandler()->clear();

//        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        return $this->redirectResponseInternal('cart');
    }
}
