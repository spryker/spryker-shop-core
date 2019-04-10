<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class DiscountWidgetRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
{
    public const ROUTE_DISCOUNT_VOUCHER_ADD = 'discount/voucher/add';
    public const ROUTE_DISCOUNT_VOUCHER_REMOVE = 'discount/voucher/remove';
    public const ROUTE_DISCOUNT_VOUCHER_CLEAR = 'discount/voucher/clear';
    public const CHECKOUT_VOUCHER_ADD = 'checkout-voucher-add';

    public const SKU_PATTERN = '[a-zA-Z0-9-_\.]+';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $routeCollection = $this->addAddVoucherRoute($routeCollection);
        $routeCollection = $this->addRemoveVoucherRoute($routeCollection);
        $routeCollection = $this->addClearVoucherRoute($routeCollection);
        $routeCollection = $this->addCheckoutVoucherRoute($routeCollection);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addAddVoucherRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/discount/voucher/add', 'DiscountWidget', 'Voucher', 'addAction');
        $routeCollection->add(static::ROUTE_DISCOUNT_VOUCHER_ADD, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addRemoveVoucherRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/discount/voucher/remove', 'DiscountWidget', 'Voucher', 'removeAction');
        $routeCollection->add(static::ROUTE_DISCOUNT_VOUCHER_REMOVE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addClearVoucherRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/discount/voucher/clear', 'DiscountWidget', 'Voucher', 'clearAction');
        $routeCollection->add(static::ROUTE_DISCOUNT_VOUCHER_CLEAR, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCheckoutVoucherRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/checkout/add-voucher', 'DiscountWidget', 'Checkout', 'addVoucherAction');
        $route = $route->method('GET|POST');
        $routeCollection->add(static::CHECKOUT_VOUCHER_ADD, $route);
        return $routeCollection;
    }
}
