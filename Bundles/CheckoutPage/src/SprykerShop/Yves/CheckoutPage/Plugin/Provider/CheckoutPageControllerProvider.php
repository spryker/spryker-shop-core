<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin` instead.
 */
class CheckoutPageControllerProvider extends AbstractYvesControllerProvider
{
    public const CHECKOUT_CUSTOMER = 'checkout-customer';
    public const CHECKOUT_ADDRESS = 'checkout-address';
    public const CHECKOUT_SHIPMENT = 'checkout-shipment';
    public const CHECKOUT_PAYMENT = 'checkout-payment';
    public const CHECKOUT_SUMMARY = 'checkout-summary';
    public const CHECKOUT_PLACE_ORDER = 'checkout-place-order';
    public const CHECKOUT_ERROR = 'checkout-error';
    public const CHECKOUT_SUCCESS = 'checkout-success';
    public const CHECKOUT_INDEX = 'checkout-index';

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
    protected function addCheckoutIndexRoute()
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
    protected function addCustomerStepRoute()
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
    protected function addAddressStepRoute()
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
    protected function addShipmentStepRoute()
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
    protected function addPaymentStepRoute()
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
    protected function addCheckoutSummaryStepRoute()
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
    protected function addPlaceOrderStepRoute()
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
    protected function addCheckoutErrorRoute()
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
    protected function addCheckoutSuccessRoute()
    {
        $this->createController('/{checkout}/success', self::CHECKOUT_SUCCESS, 'CheckoutPage', 'Checkout', 'success')
            ->assert('checkout', $this->getAllowedLocalesPattern() . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        return $this;
    }
}
