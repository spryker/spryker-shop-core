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
        $this->addCheckoutIndexRoute()
            ->addCustomerStepRoute()
            ->addAddressStepRoute()
            ->addShipmentStepRoute()
            ->addPaymentStepRoute()
            ->addCheckoutSummaryStepRoute()
            ->addPlaceOrderStepRoute()
            ->addCheckoutErrorRoute()
            ->addCheckoutSuccessRoute();
    }

    /**
     * @return $this
     */
    protected function addCheckoutIndexRoute(): self
    {
        $this->createController('/{checkout}', self::CHECKOUT_INDEX, 'CheckoutPage', 'Checkout', 'index')
            ->assert('checkout', $this->getAllowedLocalesPattern() . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerStepRoute(): self
    {
        $this->createController('/{checkout}/customer', self::CHECKOUT_CUSTOMER, 'CheckoutPage', 'Checkout', 'customer')
            ->assert('checkout', $this->getAllowedLocalesPattern() . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addAddressStepRoute(): self
    {
        $this->createController('/{checkout}/address', self::CHECKOUT_ADDRESS, 'CheckoutPage', 'Checkout', 'address')
            ->assert('checkout', $this->getAllowedLocalesPattern() . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addShipmentStepRoute(): self
    {
        $this->createController('/{checkout}/shipment', self::CHECKOUT_SHIPMENT, 'CheckoutPage', 'Checkout', 'shipment')
            ->assert('checkout', $this->getAllowedLocalesPattern() . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addPaymentStepRoute(): self
    {
        $this->createController('/{checkout}/payment', self::CHECKOUT_PAYMENT, 'CheckoutPage', 'Checkout', 'payment')
            ->assert('checkout', $this->getAllowedLocalesPattern() . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCheckoutSummaryStepRoute(): self
    {
        $this->createController('/{checkout}/summary', self::CHECKOUT_SUMMARY, 'CheckoutPage', 'Checkout', 'summary')
            ->assert('checkout', $this->getAllowedLocalesPattern() . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addPlaceOrderStepRoute(): self
    {
        $this->createController('/{checkout}/place-order', self::CHECKOUT_PLACE_ORDER, 'CheckoutPage', 'Checkout', 'placeOrder')
            ->assert('checkout', $this->getAllowedLocalesPattern() . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCheckoutErrorRoute(): self
    {
        $this->createController('/{checkout}/error', self::CHECKOUT_ERROR, 'CheckoutPage', 'Checkout', 'error')
            ->assert('checkout', $this->getAllowedLocalesPattern() . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCheckoutSuccessRoute(): self
    {
        $this->createController('/{checkout}/success', self::CHECKOUT_SUCCESS, 'CheckoutPage', 'Checkout', 'success')
            ->assert('checkout', $this->getAllowedLocalesPattern() . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        return $this;
    }
}
