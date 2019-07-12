<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CheckoutPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const CHECKOUT_CUSTOMER = 'checkout-customer';
    protected const CHECKOUT_ADDRESS = 'checkout-address';
    protected const CHECKOUT_SHIPMENT = 'checkout-shipment';
    protected const CHECKOUT_PAYMENT = 'checkout-payment';
    protected const CHECKOUT_SUMMARY = 'checkout-summary';
    protected const CHECKOUT_PLACE_ORDER = 'checkout-place-order';
    protected const CHECKOUT_ERROR = 'checkout-error';
    protected const CHECKOUT_SUCCESS = 'checkout-success';
    protected const CHECKOUT_INDEX = 'checkout-index';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
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
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCheckoutIndexRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout', 'CheckoutPage', 'Checkout', 'indexAction');
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::CHECKOUT_INDEX, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerStepRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/customer', 'CheckoutPage', 'Checkout', 'customerAction');
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::CHECKOUT_CUSTOMER, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAddressStepRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/address', 'CheckoutPage', 'Checkout', 'addressAction');
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::CHECKOUT_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShipmentStepRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/shipment', 'CheckoutPage', 'Checkout', 'shipmentAction');
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::CHECKOUT_SHIPMENT, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPaymentStepRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/payment', 'CheckoutPage', 'Checkout', 'paymentAction');
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::CHECKOUT_PAYMENT, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCheckoutSummaryStepRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/summary', 'CheckoutPage', 'Checkout', 'summaryAction');
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::CHECKOUT_SUMMARY, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPlaceOrderStepRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/place-order', 'CheckoutPage', 'Checkout', 'placeOrderAction');
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::CHECKOUT_PLACE_ORDER, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCheckoutErrorRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/error', 'CheckoutPage', 'Checkout', 'errorAction');
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::CHECKOUT_ERROR, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCheckoutSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/success', 'CheckoutPage', 'Checkout', 'successAction');
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::CHECKOUT_SUCCESS, $route);

        return $routeCollection;
    }
}
