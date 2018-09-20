<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\DiscountWidget\DiscountWidgetFactory getFactory()
 */
class DiscountVoucherFormWidget extends AbstractWidget
{
    public function __construct()
    {
        $voucherForm = $this->getFactory()
            ->getCartVoucherForm();

        $this->addParameter('voucherForm', $voucherForm->createView());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'DiscountVoucherFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@DiscountWidget/views/cart-discount-voucher-form/cart-discount-voucher-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function getVoucherForm()
    {
        return $this->getFactory()->getCheckoutVoucherForm()->createView();
    }
}
