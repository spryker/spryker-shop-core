<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use {@link \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin} instead.
 */
class CheckoutPageControllerProvider extends AbstractYvesControllerProvider
{
    /**
     * @var string
     */
    public const CHECKOUT_CUSTOMER = 'checkout-customer';

    /**
     * @var string
     */
    public const CHECKOUT_ADDRESS = 'checkout-address';

    /**
     * @var string
     */
    public const CHECKOUT_SHIPMENT = 'checkout-shipment';

    /**
     * @var string
     */
    public const CHECKOUT_PAYMENT = 'checkout-payment';

    /**
     * @var string
     */
    public const CHECKOUT_SUMMARY = 'checkout-summary';

    /**
     * @var string
     */
    public const CHECKOUT_PLACE_ORDER = 'checkout-place-order';

    /**
     * @var string
     */
    public const CHECKOUT_ERROR = 'checkout-error';

    /**
     * @var string
     */
    public const CHECKOUT_SUCCESS = 'checkout-success';

    /**
     * @var string
     */
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
        $this->createController('/{checkout}', static::CHECKOUT_INDEX, 'CheckoutPage', 'Checkout', 'index')
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
        $this->createController('/{checkout}/customer', static::CHECKOUT_CUSTOMER, 'CheckoutPage', 'Checkout', 'customer')
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
        $this->createController('/{checkout}/address', static::CHECKOUT_ADDRESS, 'CheckoutPage', 'Checkout', 'address')
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
        $this->createController('/{checkout}/shipment', static::CHECKOUT_SHIPMENT, 'CheckoutPage', 'Checkout', 'shipment')
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
        $this->createController('/{checkout}/payment', static::CHECKOUT_PAYMENT, 'CheckoutPage', 'Checkout', 'payment')
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
        $this->createController('/{checkout}/summary', static::CHECKOUT_SUMMARY, 'CheckoutPage', 'Checkout', 'summary')
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
        $this->createController('/{checkout}/place-order', static::CHECKOUT_PLACE_ORDER, 'CheckoutPage', 'Checkout', 'placeOrder')
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
        $this->createController('/{checkout}/error', static::CHECKOUT_ERROR, 'CheckoutPage', 'Checkout', 'error')
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
        $this->createController('/{checkout}/success', static::CHECKOUT_SUCCESS, 'CheckoutPage', 'Checkout', 'success')
            ->assert('checkout', $this->getAllowedLocalesPattern() . 'checkout|checkout')
            ->value('checkout', 'checkout')
            ->method('GET|POST');

        return $this;
    }
}
