<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Plugin\Router;

use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class CheckoutPageRouteProviderPlugin extends AbstractRouteProviderPlugin
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
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addCheckoutIndexRoute($routeCollection);
        $routeCollection = $this->addCustomerStepRoute($routeCollection);
        $routeCollection = $this->addAddressStepRoute($routeCollection);
        $routeCollection = $this->addShipmentStepRoute($routeCollection);
        $routeCollection = $this->addPaymentStepRoute($routeCollection);
        $routeCollection = $this->addCheckoutSummaryStepRoute($routeCollection);
        $routeCollection = $this->addPlaceOrderStepRoute($routeCollection);
        $routeCollection = $this->addCheckoutErrorRoute($routeCollection);
        $routeCollection = $this->addCheckoutSuccessRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCheckoutIndexRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout', 'CheckoutPage', 'Checkout', 'index')
            ->method('GET|POST');

        $routeCollection->add(static::CHECKOUT_INDEX, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerStepRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/customer', 'CheckoutPage', 'Checkout', 'customer')
            ->method('GET|POST');

        $routeCollection->add(static::CHECKOUT_CUSTOMER, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addAddressStepRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/address', 'CheckoutPage', 'Checkout', 'address')
            ->method('GET|POST');

        $routeCollection->add(static::CHECKOUT_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShipmentStepRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/shipment', 'CheckoutPage', 'Checkout', 'shipment')
            ->method('GET|POST');

        $routeCollection->add(static::CHECKOUT_SHIPMENT, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addPaymentStepRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/payment', 'CheckoutPage', 'Checkout', 'payment')
            ->method('GET|POST');

        $routeCollection->add(static::CHECKOUT_PAYMENT, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCheckoutSummaryStepRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/summary', 'CheckoutPage', 'Checkout', 'summary')
            ->method('GET|POST');

        $routeCollection->add(static::CHECKOUT_SUMMARY, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addPlaceOrderStepRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/place-order', 'CheckoutPage', 'Checkout', 'placeOrder')
            ->method('GET|POST');

        $routeCollection->add(static::CHECKOUT_PLACE_ORDER, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCheckoutErrorRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/error', 'CheckoutPage', 'Checkout', 'error')
            ->method('GET|POST');

        $routeCollection->add(static::CHECKOUT_ERROR, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCheckoutSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/success', 'CheckoutPage', 'Checkout', 'success')
            ->method('GET|POST');

        $routeCollection->add(static::CHECKOUT_SUCCESS, $route);

        return $routeCollection;
    }
}
