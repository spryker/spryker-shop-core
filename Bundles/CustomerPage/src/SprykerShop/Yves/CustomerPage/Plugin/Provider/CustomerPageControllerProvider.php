<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin` instead.
 */
class CustomerPageControllerProvider extends AbstractYvesControllerProvider
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
    protected const ROUTE_TOKEN = 'token';

    protected const TOKEN_PATTERN = '[a-zA-Z0-9-_\.]+';

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
            ->addCustomerAddressRoute();

        $this->addNewCustomerAddressRoute()
            ->addUpdateCustomerAddressRoute()
            ->addDeleteCustomerAddressRoute()
            ->addRefreshCustomerAddressRoute()
            ->addCustomerOrderRoute()
            ->addCustomerOrderDetailsRoute()
            ->addCustomerDeleteRoute()
            ->addCustomerDeleteConfirmRoute()
            ->addAccessTokenRoute();
    }

    /**
     * @return $this
     */
    protected function addLoginRoute()
    {
        $this->createController('/{login}', self::ROUTE_LOGIN, 'CustomerPage', 'Auth', 'login')
            ->assert('login', $this->getAllowedLocalesPattern() . 'login|login')
            ->value('login', 'login');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addLogoutRoute()
    {
        $this->createController('/{logout}', self::ROUTE_LOGOUT, 'CustomerPage', 'Auth', 'logout')
            ->assert('logout', $this->getAllowedLocalesPattern() . 'logout|logout')
            ->value('logout', 'logout');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRegisterRoute()
    {
        $this->createController('/{register}', self::ROUTE_REGISTER, 'CustomerPage', 'Register', 'index')
            ->assert('register', $this->getAllowedLocalesPattern() . 'register|register')
            ->value('register', 'register');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addForgottenPasswordRoute()
    {
        $this->createController('/{password}/forgotten', self::ROUTE_PASSWORD_FORGOTTEN, 'CustomerPage', 'Password', 'forgottenPassword')
            ->assert('password', $this->getAllowedLocalesPattern() . 'password|password')
            ->value('password', 'password');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRestorePasswordRoute()
    {
        $this->createController('/{password}/restore', self::ROUTE_PASSWORD_RESTORE, 'CustomerPage', 'Password', 'restorePassword')
            ->assert('password', $this->getAllowedLocalesPattern() . 'password|password')
            ->value('password', 'password');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerOverviewRoute()
    {
        $this->createController('/{customer}/overview', self::ROUTE_CUSTOMER_OVERVIEW, 'CustomerPage', 'Customer', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerProfileRoute()
    {
        $this->createController('/{customer}/profile', self::ROUTE_CUSTOMER_PROFILE, 'CustomerPage', 'Profile', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerAddressRoute()
    {
        $this->createController('/{customer}/address', self::ROUTE_CUSTOMER_ADDRESS, 'CustomerPage', 'Address', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addNewCustomerAddressRoute()
    {
        $this->createController('/{customer}/address/new', self::ROUTE_CUSTOMER_NEW_ADDRESS, 'CustomerPage', 'Address', 'create')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUpdateCustomerAddressRoute()
    {
        $this->createController('/{customer}/address/update', self::ROUTE_CUSTOMER_UPDATE_ADDRESS, 'CustomerPage', 'Address', 'update')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDeleteCustomerAddressRoute()
    {
        $this->createController('/{customer}/address/delete', self::ROUTE_CUSTOMER_DELETE_ADDRESS, 'CustomerPage', 'Address', 'delete')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRefreshCustomerAddressRoute()
    {
        $this->createController('/{customer}/address/refresh', self::ROUTE_CUSTOMER_REFRESH_ADDRESS, 'CustomerPage', 'Address', 'refresh')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerOrderRoute()
    {
        $this->createController('/{customer}/order', self::ROUTE_CUSTOMER_ORDER, 'CustomerPage', 'Order', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerOrderDetailsRoute()
    {
        $this->createController('/{customer}/order/details', self::ROUTE_CUSTOMER_ORDER_DETAILS, 'CustomerPage', 'Order', 'details')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerDeleteRoute()
    {
        $this->createController('/{customer}/delete', self::ROUTE_CUSTOMER_DELETE, 'CustomerPage', 'Delete', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerDeleteConfirmRoute()
    {
        $this->createController('/{customer}/delete/confirm', self::ROUTE_CUSTOMER_DELETE_CONFIRM, 'CustomerPage', 'Delete', 'confirm')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Controller\AccessTokenController::indexAction()
     *
     * @return $this
     */
    protected function addAccessTokenRoute()
    {
        $this->createController('/{accessToken}/{token}', static::ROUTE_TOKEN, 'CustomerPage', 'AccessToken', 'index')
            ->assert('accessToken', $this->getAllowedLocalesPattern() . 'access-token|access-token')
            ->value('accessToken', 'access-token')
            ->assert('token', static::TOKEN_PATTERN);

        return $this;
    }
}
