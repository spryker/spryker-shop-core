<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CheckoutPage\Dependency\Plugin\DiscountWidget\CheckoutVoucherFormWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\DiscountWidget\DiscountWidgetFactory getFactory()
 */
class CheckoutVoucherFormWidgetPlugin extends AbstractWidgetPlugin implements CheckoutVoucherFormWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
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
        return static::NAME;
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
