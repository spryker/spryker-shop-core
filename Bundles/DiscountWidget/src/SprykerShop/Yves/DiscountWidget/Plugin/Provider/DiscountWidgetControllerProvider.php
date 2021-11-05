<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use {@link \SprykerShop\Yves\DiscountWidget\Plugin\Router\DiscountWidgetRouteProviderPlugin} instead.
 */
class DiscountWidgetControllerProvider extends AbstractYvesControllerProvider
{
    /**
     * @var string
     */
    public const ROUTE_DISCOUNT_VOUCHER_ADD = 'discount/voucher/add';

    /**
     * @var string
     */
    public const ROUTE_DISCOUNT_VOUCHER_REMOVE = 'discount/voucher/remove';

    /**
     * @var string
     */
    public const ROUTE_DISCOUNT_VOUCHER_CLEAR = 'discount/voucher/clear';

    /**
     * @var string
     */
    public const CHECKOUT_VOUCHER_ADD = 'checkout-voucher-add';

    /**
     * @var string
     */
    public const SKU_PATTERN = '[a-zA-Z0-9-_\.]+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addAddVoucherRoute()
            ->addRemoveVoucherRoute()
            ->addClearVoucherRoute()
            ->addCheckoutVoucherRoute();
    }

    /**
     * @return $this
     */
    protected function addAddVoucherRoute()
    {
        $this->createController('/{discount}/voucher/add', static::ROUTE_DISCOUNT_VOUCHER_ADD, 'DiscountWidget', 'Voucher', 'add')
            ->assert('discount', $this->getAllowedLocalesPattern() . 'discount|discount')
            ->value('discount', 'discount');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRemoveVoucherRoute()
    {
        $this->createController('/{discount}/voucher/remove', static::ROUTE_DISCOUNT_VOUCHER_REMOVE, 'DiscountWidget', 'Voucher', 'remove')
            ->assert('discount', $this->getAllowedLocalesPattern() . 'discount|discount')
            ->value('discount', 'discount');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addClearVoucherRoute()
    {
        $this->createController('/{discount}/voucher/clear', static::ROUTE_DISCOUNT_VOUCHER_CLEAR, 'DiscountWidget', 'Voucher', 'clear')
            ->assert('discount', $this->getAllowedLocalesPattern() . 'discount|discount')
            ->value('discount', 'discount');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCheckoutVoucherRoute()
    {
        $this->createController('/{checkout}/add-voucher', static::CHECKOUT_VOUCHER_ADD, 'DiscountWidget', 'Checkout', 'addVoucher')
            ->assert('checkout', $this->getAllowedLocalesPattern() . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        return $this;
    }
}
