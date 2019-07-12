<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class DiscountWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_DISCOUNT_VOUCHER_ADD = 'discount/voucher/add';
    protected const ROUTE_DISCOUNT_VOUCHER_REMOVE = 'discount/voucher/remove';
    protected const ROUTE_DISCOUNT_VOUCHER_CLEAR = 'discount/voucher/clear';
    protected const CHECKOUT_VOUCHER_ADD = 'checkout-voucher-add';

    protected const SKU_PATTERN = '[a-zA-Z0-9-_\.]+';

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
        $routeCollection = $this->addAddVoucherRoute($routeCollection);
        $routeCollection = $this->addRemoveVoucherRoute($routeCollection);
        $routeCollection = $this->addClearVoucherRoute($routeCollection);
        $routeCollection = $this->addCheckoutVoucherRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAddVoucherRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/discount/voucher/add', 'DiscountWidget', 'Voucher', 'addAction');
        $routeCollection->add(static::ROUTE_DISCOUNT_VOUCHER_ADD, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRemoveVoucherRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/discount/voucher/remove', 'DiscountWidget', 'Voucher', 'removeAction');
        $routeCollection->add(static::ROUTE_DISCOUNT_VOUCHER_REMOVE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addClearVoucherRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/discount/voucher/clear', 'DiscountWidget', 'Voucher', 'clearAction');
        $routeCollection->add(static::ROUTE_DISCOUNT_VOUCHER_CLEAR, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCheckoutVoucherRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/add-voucher', 'DiscountWidget', 'Checkout', 'addVoucherAction');
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::CHECKOUT_VOUCHER_ADD, $route);

        return $routeCollection;
    }
}
