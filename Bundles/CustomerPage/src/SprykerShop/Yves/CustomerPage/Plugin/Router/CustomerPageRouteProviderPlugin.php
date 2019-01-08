<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Router;

use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class CustomerPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_LOGIN = 'login';
    public const ROUTE_LOGOUT = 'logout';
    public const ROUTE_REGISTER = 'register';
    public const ROUTE_PASSWORD_FORGOTTEN = 'password/forgotten';
    public const ROUTE_PASSWORD_RESTORE = 'password/restore';
    public const ROUTE_CUSTOMER_OVERVIEW = 'customer/overview';
    public const ROUTE_CUSTOMER_PROFILE = 'customer/profile';
    public const ROUTE_CUSTOMER_ADDRESS = 'customer/address';
    public const ROUTE_CUSTOMER_NEW_ADDRESS = 'customer/address/new';
    public const ROUTE_CUSTOMER_UPDATE_ADDRESS = 'customer/address/update';
    public const ROUTE_CUSTOMER_DELETE_ADDRESS = 'customer/address/delete';
    public const ROUTE_CUSTOMER_REFRESH_ADDRESS = 'customer/address/refresh';
    public const ROUTE_CUSTOMER_ORDER = 'customer/order';
    public const ROUTE_CUSTOMER_ORDER_DETAILS = 'customer/order/details';
    public const ROUTE_CUSTOMER_DELETE = 'customer/delete';
    public const ROUTE_CUSTOMER_DELETE_CONFIRM = 'customer/delete/confirm';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addLoginRoute($routeCollection);
        $routeCollection = $this->addLogoutRoute($routeCollection);
        $routeCollection = $this->addRegisterRoute($routeCollection);
        $routeCollection = $this->addForgottenPasswordRoute($routeCollection);
        $routeCollection = $this->addRestorePasswordRoute($routeCollection);
        $routeCollection = $this->addCustomerOverviewRoute($routeCollection);
        $routeCollection = $this->addCustomerProfileRoute($routeCollection);
        $routeCollection = $this->addCustomerAddressRoute($routeCollection);
        $routeCollection = $this->addNewCustomerAddressRoute($routeCollection);
        $routeCollection = $this->addUpdateCustomerAddressRoute($routeCollection);
        $routeCollection = $this->addDeleteCustomerAddressRoute($routeCollection);
        $routeCollection = $this->addRefreshCustomerAddressRoute($routeCollection);
        $routeCollection = $this->addCustomerOrderRoute($routeCollection);
        $routeCollection = $this->addCustomerOrderDetailsRoute($routeCollection);
        $routeCollection = $this->addCustomerDeleteRoute($routeCollection);
        $routeCollection = $this->addCustomerDeleteConfirmRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addLoginRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/login', 'CustomerPage', 'Auth', 'login');
        $routeCollection->add(static::ROUTE_LOGIN, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addLogoutRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/logout', 'CustomerPage', 'Auth', 'logout');
        $routeCollection->add(static::ROUTE_LOGOUT, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addRegisterRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/register', 'CustomerPage', 'Register', 'index');
        $routeCollection->add(static::ROUTE_REGISTER, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addForgottenPasswordRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/password/forgotten', 'CustomerPage', 'Password', 'forgottenPassword');
        $routeCollection->add(static::ROUTE_PASSWORD_FORGOTTEN, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addRestorePasswordRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/password/restore', 'CustomerPage', 'Password', 'restorePassword');
        $routeCollection->add(static::ROUTE_PASSWORD_RESTORE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerOverviewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/overview', 'CustomerPage', 'Customer', 'index');
        $routeCollection->add(static::ROUTE_CUSTOMER_OVERVIEW, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerProfileRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/profile', 'CustomerPage', 'Profile', 'index');
        $routeCollection->add(static::ROUTE_CUSTOMER_PROFILE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerAddressRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/address', 'CustomerPage', 'Address', 'index');
        $routeCollection->add(static::ROUTE_CUSTOMER_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addNewCustomerAddressRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/address/new', 'CustomerPage', 'Address', 'create');
        $routeCollection->add(static::ROUTE_CUSTOMER_NEW_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addUpdateCustomerAddressRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/address/update', 'CustomerPage', 'Address', 'update');
        $routeCollection->add(static::ROUTE_CUSTOMER_UPDATE_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addDeleteCustomerAddressRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/address/delete', 'CustomerPage', 'Address', 'delete');
        $routeCollection->add(static::ROUTE_CUSTOMER_DELETE_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addRefreshCustomerAddressRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/address/refresh', 'CustomerPage', 'Address', 'refresh');
        $routeCollection->add(static::ROUTE_CUSTOMER_REFRESH_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerOrderRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/order', 'CustomerPage', 'Order', 'index');
        $routeCollection->add(static::ROUTE_CUSTOMER_ORDER, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerOrderDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/order/details', 'CustomerPage', 'Order', 'details');
        $routeCollection->add(static::ROUTE_CUSTOMER_ORDER_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerDeleteRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/delete', 'CustomerPage', 'Delete', 'index');
        $routeCollection->add(static::ROUTE_CUSTOMER_DELETE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerDeleteConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/delete/confirm', 'CustomerPage', 'Delete', 'confirm');
        $routeCollection->add(static::ROUTE_CUSTOMER_DELETE_CONFIRM, $route);

        return $routeCollection;
    }
}
