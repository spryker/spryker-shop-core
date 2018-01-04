<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CheckoutPageControllerProvider extends AbstractYvesControllerProvider
{
    const CHECKOUT_CUSTOMER = 'checkout-customer';
    const CHECKOUT_ADDRESS = 'checkout-address';
    const CHECKOUT_SHIPMENT = 'checkout-shipment';
    const CHECKOUT_PAYMENT = 'checkout-payment';
    const CHECKOUT_SUMMARY = 'checkout-summary';
    const CHECKOUT_PLACE_ORDER = 'checkout-place-order';
    const CHECKOUT_ERROR = 'checkout-error';
    const CHECKOUT_SUCCESS = 'checkout-success';
    const CHECKOUT_INDEX = 'checkout-index';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{checkout}', self::CHECKOUT_INDEX, 'CheckoutPage', 'Checkout', 'index')
            ->assert('checkout', $allowedLocalesPattern . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        $this->createController('/{checkout}/customer', self::CHECKOUT_CUSTOMER, 'CheckoutPage', 'Checkout', 'customer')
            ->assert('checkout', $allowedLocalesPattern . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        $this->createController('/{checkout}/address', self::CHECKOUT_ADDRESS, 'CheckoutPage', 'Checkout', 'address')
            ->assert('checkout', $allowedLocalesPattern . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        $this->createController('/{checkout}/shipment', self::CHECKOUT_SHIPMENT, 'CheckoutPage', 'Checkout', 'shipment')
            ->assert('checkout', $allowedLocalesPattern . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        $this->createController('/{checkout}/payment', self::CHECKOUT_PAYMENT, 'CheckoutPage', 'Checkout', 'payment')
            ->assert('checkout', $allowedLocalesPattern . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        $this->createController('/{checkout}/summary', self::CHECKOUT_SUMMARY, 'CheckoutPage', 'Checkout', 'summary')
            ->assert('checkout', $allowedLocalesPattern . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        $this->createController('/{checkout}/place-order', self::CHECKOUT_PLACE_ORDER, 'CheckoutPage', 'Checkout', 'placeOrder')
            ->assert('checkout', $allowedLocalesPattern . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        $this->createController('/{checkout}/error', self::CHECKOUT_ERROR, 'CheckoutPage', 'Checkout', 'error')
            ->assert('checkout', $allowedLocalesPattern . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        $this->createController('/{checkout}/success', self::CHECKOUT_SUCCESS, 'CheckoutPage', 'Checkout', 'success')
            ->assert('checkout', $allowedLocalesPattern . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');
    }
}
