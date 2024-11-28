<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class PaymentAppWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_EXPRESS_CHECKOUT_PAYMENT_WIDGET_CONTENT = 'express-checkout/payment-widget-content';

    /**
     * @var string
     */
    public const ROUTE_NAME_EXPRESS_CHECKOUT_PRE_ORDER = 'express-checkout/pre-order';

    /**
     * @var string
     */
    public const ROUTE_NAME_EXPRESS_CHECKOUT_SUCCESS = 'express-checkout/success';

    /**
     * @var string
     */
    public const ROUTE_NAME_EXPRESS_CHECKOUT_CANCEL = 'express-checkout/cancel';

    /**
     * @var string
     */
    public const ROUTE_NAME_EXPRESS_CHECKOUT_FAILURE = 'express-checkout/failure';

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
        $routeCollection = $this->addExpressCheckoutPaymentWidgetContentRoute($routeCollection);
        $routeCollection = $this->addExpressCheckoutPreOrderRoute($routeCollection);
        $routeCollection = $this->addExpressCheckoutSuccessRoute($routeCollection);
        $routeCollection = $this->addExpressCheckoutCancelRoute($routeCollection);
        $routeCollection = $this->addExpressCheckoutFailureRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\PaymentAppWidget\Controller\ExpressCheckoutPaymentWidgetContentController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addExpressCheckoutPaymentWidgetContentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/express-checkout/payment-widget-content', 'PaymentAppWidget', 'ExpressCheckoutPaymentWidgetContent', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_EXPRESS_CHECKOUT_PAYMENT_WIDGET_CONTENT, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\PaymentAppWidget\Controller\ExpressCheckoutSuccessController::successAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addExpressCheckoutSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildPostRoute('/express-checkout/success', 'PaymentAppWidget', 'ExpressCheckoutSuccess', 'successAction');
        $routeCollection->add(static::ROUTE_NAME_EXPRESS_CHECKOUT_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\PaymentAppWidget\Controller\ExpressCheckoutCancelController::cancelAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addExpressCheckoutCancelRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildPostRoute('/express-checkout/cancel', 'PaymentAppWidget', 'ExpressCheckoutCancel', 'cancelAction');
        $routeCollection->add(static::ROUTE_NAME_EXPRESS_CHECKOUT_CANCEL, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\PaymentAppWidget\Controller\ExpressCheckoutFailureController::failureAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addExpressCheckoutFailureRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/express-checkout/failure', 'PaymentAppWidget', 'ExpressCheckoutFailure', 'failureAction');
        $routeCollection->add(static::ROUTE_NAME_EXPRESS_CHECKOUT_FAILURE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\PaymentAppWidget\Controller\ExpressCheckoutPreOrderController::preOrderAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addExpressCheckoutPreOrderRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildPostRoute('/express-checkout/pre-order', 'PaymentAppWidget', 'ExpressCheckoutPreOrder', 'preOrderAction');
        $routeCollection->add(static::ROUTE_NAME_EXPRESS_CHECKOUT_PRE_ORDER, $route);

        return $routeCollection;
    }
}
