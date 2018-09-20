<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\DiscountWidget\DiscountWidgetFactory getFactory()
 */
class CheckoutVoucherFormWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this
            ->addParameter('voucherForm', $this->getVoucherForm())
            ->addParameter('quoteTransfer', $quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CheckoutVoucherFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@DiscountWidget/views/checkout-summary-dicount-voucher-form/checkout-summary-dicount-voucher-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function getVoucherForm()
    {
        return $this->getFactory()->getCheckoutVoucherForm()->createView();
    }
}
