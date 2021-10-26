<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Controller;

use SprykerShop\Yves\DiscountWidget\Form\CheckoutVoucherForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\DiscountWidget\DiscountWidgetFactory getFactory()
 */
class CheckoutController extends AbstractController
{
    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SUMMARY
     *
     * @var string
     */
    protected const ROUTE_CHECKOUT_SUMMARY = 'checkout-summary';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addVoucherAction(Request $request)
    {
        $form = $this->getFactory()
            ->getCheckoutVoucherForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voucherCode = $form->get(CheckoutVoucherForm::FIELD_VOUCHER_DISCOUNTS)->getData();

            $this->getFactory()
                ->createVoucherHandler()
                ->add($voucherCode);
        }

        return $this->redirectResponseInternal(static::ROUTE_CHECKOUT_SUMMARY);
    }
}
