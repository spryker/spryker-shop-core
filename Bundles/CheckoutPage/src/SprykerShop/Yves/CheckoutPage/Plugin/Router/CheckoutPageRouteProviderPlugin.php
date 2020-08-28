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
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_CUSTOMER} instead.
     */
    protected const CHECKOUT_CUSTOMER = 'checkout-customer';
    public const ROUTE_NAME_CHECKOUT_CUSTOMER = 'checkout-customer';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_ADDRESS} instead.
     */
    protected const CHECKOUT_ADDRESS = 'checkout-address';
    public const ROUTE_NAME_CHECKOUT_ADDRESS = 'checkout-address';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SHIPMENT} instead.
     */
    protected const CHECKOUT_SHIPMENT = 'checkout-shipment';
    public const ROUTE_NAME_CHECKOUT_SHIPMENT = 'checkout-shipment';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PAYMENT} instead.
     */
    protected const CHECKOUT_PAYMENT = 'checkout-payment';
    public const ROUTE_NAME_CHECKOUT_PAYMENT = 'checkout-payment';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SUMMARY} instead.
     */
    protected const CHECKOUT_SUMMARY = 'checkout-summary';
    public const ROUTE_NAME_CHECKOUT_SUMMARY = 'checkout-summary';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PLACE_ORDER} instead.
     */
    protected const CHECKOUT_PLACE_ORDER = 'checkout-place-order';
    public const ROUTE_NAME_CHECKOUT_PLACE_ORDER = 'checkout-place-order';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_ERROR} instead.
     */
    protected const CHECKOUT_ERROR = 'checkout-error';
    public const ROUTE_NAME_CHECKOUT_ERROR = 'checkout-error';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_INDEX} instead.
     */
    protected const CHECKOUT_SUCCESS = 'checkout-success';
    public const ROUTE_NAME_CHECKOUT_SUCCESS = 'checkout-success';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_INDEX} instead.
     */
    protected const CHECKOUT_INDEX = 'checkout-index';
    public const ROUTE_NAME_CHECKOUT_INDEX = 'checkout-index';

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
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_INDEX, $route);

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
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_CUSTOMER, $route);

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
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_ADDRESS, $route);

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
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_SHIPMENT, $route);

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
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_PAYMENT, $route);

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
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_SUMMARY, $route);

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
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_PLACE_ORDER, $route);

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
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_ERROR, $route);

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
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_SUCCESS, $route);

        return $routeCollection;
    }
}
