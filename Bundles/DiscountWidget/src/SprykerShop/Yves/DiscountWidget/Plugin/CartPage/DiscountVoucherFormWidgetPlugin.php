<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Plugin\CartPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\DiscountWidget\DiscountVoucherFormWidgetPluginInterface;
use SprykerShop\Yves\DiscountWidget\DiscountWidgetFactory;

/**
 * Class DiscountVoucherFormWidgetPlugin
 *
 * @method DiscountWidgetFactory getFactory()
 */
class DiscountVoucherFormWidgetPlugin extends AbstractWidgetPlugin implements DiscountVoucherFormWidgetPluginInterface
{

    /**
     * @return void
     */
    public function initialize(): void
    {
        $voucherForm = $this->getFactory()
            ->getVoucherForm();

        $this->addParameter('voucherForm', $voucherForm->createView());
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@DiscountWidget/_cart-page/discount-voucher-form.twig';
    }
}
