<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class PaymentPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
 /**
  * @var string
  */
    public const ROUTE_NAME_PAYMENT_ORDER_SUCCESS = 'payment-success';

    /**
     * @var string
     */
    public const ROUTE_NAME_PAYMENT_ORDER_CANCEL = 'payment-cancel';

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
        $routeCollection = $this->addPaymentOrderSuccessRoute($routeCollection);
        $routeCollection = $this->addPaymentOrderCancelRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\PaymentPage\Controller\PaymentSuccessController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPaymentOrderSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildGetRoute('/payment/order-success', 'PaymentPage', 'PaymentSuccess');
        $routeCollection->add(static::ROUTE_NAME_PAYMENT_ORDER_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\PaymentPage\Controller\PaymentCancelController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPaymentOrderCancelRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildGetRoute('/payment/order-cancel', 'PaymentPage', 'PaymentCancel');
        $routeCollection->add(static::ROUTE_NAME_PAYMENT_ORDER_CANCEL, $route);

        return $routeCollection;
    }
}
