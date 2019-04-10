<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CheckoutPageRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
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
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
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
    protected function addCheckoutIndexRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/checkout', 'CheckoutPage', 'Checkout', 'indexAction');
        $route = $route->method('GET|POST');
        $routeCollection->add(static::CHECKOUT_INDEX, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerStepRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/checkout/customer', 'CheckoutPage', 'Checkout', 'customerAction');
        $route = $route->method('GET|POST');
        $routeCollection->add(static::CHECKOUT_CUSTOMER, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addAddressStepRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/checkout/address', 'CheckoutPage', 'Checkout', 'addressAction');
        $route = $route->method('GET|POST');
        $routeCollection->add(static::CHECKOUT_ADDRESS, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShipmentStepRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/checkout/shipment', 'CheckoutPage', 'Checkout', 'shipmentAction');
        $route = $route->method('GET|POST');
        $routeCollection->add(static::CHECKOUT_SHIPMENT, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addPaymentStepRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/checkout/payment', 'CheckoutPage', 'Checkout', 'paymentAction');
        $route = $route->method('GET|POST');
        $routeCollection->add(static::CHECKOUT_PAYMENT, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCheckoutSummaryStepRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/checkout/summary', 'CheckoutPage', 'Checkout', 'summaryAction');
        $route = $route->method('GET|POST');
        $routeCollection->add(static::CHECKOUT_SUMMARY, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addPlaceOrderStepRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/checkout/place-order', 'CheckoutPage', 'Checkout', 'placeOrderAction');
        $route = $route->method('GET|POST');
        $routeCollection->add(static::CHECKOUT_PLACE_ORDER, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCheckoutErrorRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/checkout/error', 'CheckoutPage', 'Checkout', 'errorAction');
        $route = $route->method('GET|POST');
        $routeCollection->add(static::CHECKOUT_ERROR, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCheckoutSuccessRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/checkout/success', 'CheckoutPage', 'Checkout', 'successAction');
        $route = $route->method('GET|POST');
        $routeCollection->add(static::CHECKOUT_SUCCESS, $route);
        return $routeCollection;
    }
}
