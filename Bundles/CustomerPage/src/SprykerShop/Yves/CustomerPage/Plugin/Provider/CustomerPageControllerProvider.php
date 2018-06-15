<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CustomerPageControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_LOGIN = 'login';
    const ROUTE_LOGOUT = 'logout';
    const ROUTE_REGISTER = 'register';
    const ROUTE_PASSWORD_FORGOTTEN = 'password/forgotten';
    const ROUTE_PASSWORD_RESTORE = 'password/restore';
    const ROUTE_CUSTOMER_OVERVIEW = 'customer/overview';
    const ROUTE_CUSTOMER_PROFILE = 'customer/profile';
    const ROUTE_CUSTOMER_ADDRESS = 'customer/address';
    const ROUTE_CUSTOMER_NEW_ADDRESS = 'customer/address/new';
    const ROUTE_CUSTOMER_UPDATE_ADDRESS = 'customer/address/update';
    const ROUTE_CUSTOMER_DELETE_ADDRESS = 'customer/address/delete';
    const ROUTE_CUSTOMER_REFRESH_ADDRESS = 'customer/address/refresh';
    const ROUTE_CUSTOMER_ORDER = 'customer/order';
    const ROUTE_CUSTOMER_ORDER_DETAILS = 'customer/order/details';
    const ROUTE_CUSTOMER_DELETE = 'customer/delete';
    const ROUTE_CUSTOMER_DELETE_CONFIRM = 'customer/delete/confirm';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addLoginRoute()
            ->addLogoutRoute()
            ->addRegisterRoute()
            ->addForgottenPasswordRoute()
            ->addRestorePasswordRoute()
            ->addCustomerOverviewRoute()
            ->addCustomerProfileRoute()
            ->addCustomerAddressRoute()
            ->addNewCustomerAddressRoute()
            ->addUpdateCustomerAddressRoute()
            ->addDeleteCustomerAddressRoute()
            ->addRefreshCustomerAddressRoute()
            ->addCustomerOrderRoute()
            ->addCustomerOrderDetailsRoute()
            ->addCustomerDeleteRoute()
            ->addCustomerDeleteConfirmRoute();
    }

    /**
     * @return $this
     */
    protected function addLoginRoute(): self
    {
        $this->createController('/{login}', self::ROUTE_LOGIN, 'CustomerPage', 'Auth', 'login')
            ->assert('login', $this->getAllowedLocalesPattern() . 'login|login')
            ->value('login', 'login');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addLogoutRoute(): self
    {
        $this->createController('/{logout}', self::ROUTE_LOGOUT, 'CustomerPage', 'Auth', 'logout')
            ->assert('logout', $this->getAllowedLocalesPattern() . 'logout|logout')
            ->value('logout', 'logout');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRegisterRoute(): self
    {
        $this->createController('/{register}', self::ROUTE_REGISTER, 'CustomerPage', 'Register', 'index')
            ->assert('register', $this->getAllowedLocalesPattern() . 'register|register')
            ->value('register', 'register');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addForgottenPasswordRoute(): self
    {
        $this->createController('/{password}/forgotten', self::ROUTE_PASSWORD_FORGOTTEN, 'CustomerPage', 'Password', 'forgottenPassword')
            ->assert('password', $this->getAllowedLocalesPattern() . 'password|password')
            ->value('password', 'password');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRestorePasswordRoute(): self
    {
        $this->createController('/{password}/restore', self::ROUTE_PASSWORD_RESTORE, 'CustomerPage', 'Password', 'restorePassword')
            ->assert('password', $this->getAllowedLocalesPattern() . 'password|password')
            ->value('password', 'password');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerOverviewRoute(): self
    {
        $this->createController('/{customer}/overview', self::ROUTE_CUSTOMER_OVERVIEW, 'CustomerPage', 'Customer', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerProfileRoute(): self
    {
        $this->createController('/{customer}/profile', self::ROUTE_CUSTOMER_PROFILE, 'CustomerPage', 'Profile', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerAddressRoute(): self
    {
        $this->createController('/{customer}/address', self::ROUTE_CUSTOMER_ADDRESS, 'CustomerPage', 'Address', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addNewCustomerAddressRoute(): self
    {
        $this->createController('/{customer}/address/new', self::ROUTE_CUSTOMER_NEW_ADDRESS, 'CustomerPage', 'Address', 'create')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUpdateCustomerAddressRoute(): self
    {
        $this->createController('/{customer}/address/update', self::ROUTE_CUSTOMER_UPDATE_ADDRESS, 'CustomerPage', 'Address', 'update')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDeleteCustomerAddressRoute(): self
    {
        $this->createController('/{customer}/address/delete', self::ROUTE_CUSTOMER_DELETE_ADDRESS, 'CustomerPage', 'Address', 'delete')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRefreshCustomerAddressRoute(): self
    {
        $this->createController('/{customer}/address/refresh', self::ROUTE_CUSTOMER_REFRESH_ADDRESS, 'CustomerPage', 'Address', 'refresh')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerOrderRoute(): self
    {
        $this->createController('/{customer}/order', self::ROUTE_CUSTOMER_ORDER, 'CustomerPage', 'Order', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerOrderDetailsRoute(): self
    {
        $this->createController('/{customer}/order/details', self::ROUTE_CUSTOMER_ORDER_DETAILS, 'CustomerPage', 'Order', 'details')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerDeleteRoute(): self
    {
        $this->createController('/{customer}/delete', self::ROUTE_CUSTOMER_DELETE, 'CustomerPage', 'Delete', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerDeleteConfirmRoute(): self
    {
        $this->createController('/{customer}/delete/confirm', self::ROUTE_CUSTOMER_DELETE_CONFIRM, 'CustomerPage', 'Delete', 'confirm')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }
}
