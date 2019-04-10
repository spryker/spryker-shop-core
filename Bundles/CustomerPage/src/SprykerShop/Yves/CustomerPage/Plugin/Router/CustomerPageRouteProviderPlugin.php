<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CustomerPageRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
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
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
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
    protected function addLoginRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/login', 'CustomerPage', 'Auth', 'loginAction');
        $routeCollection->add(static::ROUTE_LOGIN, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addLogoutRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/logout', 'CustomerPage', 'Auth', 'logoutAction');
        $routeCollection->add(static::ROUTE_LOGOUT, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addRegisterRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/register', 'CustomerPage', 'Register', 'indexAction');
        $routeCollection->add(static::ROUTE_REGISTER, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addForgottenPasswordRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/password/forgotten', 'CustomerPage', 'Password', 'forgottenPasswordAction');
        $routeCollection->add(static::ROUTE_PASSWORD_FORGOTTEN, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addRestorePasswordRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/password/restore', 'CustomerPage', 'Password', 'restorePasswordAction');
        $routeCollection->add(static::ROUTE_PASSWORD_RESTORE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerOverviewRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/customer/overview', 'CustomerPage', 'Customer', 'indexAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_OVERVIEW, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerProfileRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/customer/profile', 'CustomerPage', 'Profile', 'indexAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_PROFILE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerAddressRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/customer/address', 'CustomerPage', 'Address', 'indexAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_ADDRESS, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addNewCustomerAddressRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/customer/address/new', 'CustomerPage', 'Address', 'createAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_NEW_ADDRESS, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addUpdateCustomerAddressRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/customer/address/update', 'CustomerPage', 'Address', 'updateAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_UPDATE_ADDRESS, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addDeleteCustomerAddressRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/customer/address/delete', 'CustomerPage', 'Address', 'deleteAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_DELETE_ADDRESS, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addRefreshCustomerAddressRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/customer/address/refresh', 'CustomerPage', 'Address', 'refreshAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_REFRESH_ADDRESS, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerOrderRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/customer/order', 'CustomerPage', 'Order', 'indexAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_ORDER, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerOrderDetailsRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/customer/order/details', 'CustomerPage', 'Order', 'detailsAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_ORDER_DETAILS, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerDeleteRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/customer/delete', 'CustomerPage', 'Delete', 'indexAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_DELETE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCustomerDeleteConfirmRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/customer/delete/confirm', 'CustomerPage', 'Delete', 'confirmAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_DELETE_CONFIRM, $route);
        return $routeCollection;
    }
}
