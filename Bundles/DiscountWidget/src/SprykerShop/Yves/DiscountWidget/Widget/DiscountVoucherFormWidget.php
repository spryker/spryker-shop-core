<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @deprecated Use \SprykerShop\Yves\CartCodeWidget\Widget\CartCodeFormWidget instead.
 *
 * @method \SprykerShop\Yves\DiscountWidget\DiscountWidgetFactory getFactory()
 */
class DiscountVoucherFormWidget extends AbstractWidget
{
    public function __construct()
    {
        $voucherForm = $this->getFactory()
            ->getCartVoucherForm();

        $this->addParameter('voucherForm', $voucherForm->createView());
        $this->addIsQuoteEditableParameter();
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
     * @return $this
     */
    protected function addIsQuoteEditableParameter()
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        return $this->addParameter('isQuoteEditable', $this->getFactory()->getQuoteClient()->isQuoteEditable($quoteTransfer));
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function getVoucherForm(): FormView
    {
        return $this->getFactory()->getCheckoutVoucherForm()->createView();
    }
}
