<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin} instead.
 */
class CustomerPageControllerProvider extends AbstractYvesControllerProvider
{
    /**
     * @var string
     */
    public const ROUTE_LOGIN = 'login';

    /**
     * @var string
     */
    public const ROUTE_LOGOUT = 'logout';

    /**
     * @var string
     */
    public const ROUTE_REGISTER = 'register';

    /**
     * @var string
     */
    public const ROUTE_PASSWORD_FORGOTTEN = 'password/forgotten';

    /**
     * @var string
     */
    public const ROUTE_PASSWORD_RESTORE = 'password/restore';

    /**
     * @var string
     */
    public const ROUTE_CUSTOMER_OVERVIEW = 'customer/overview';

    /**
     * @var string
     */
    public const ROUTE_CUSTOMER_PROFILE = 'customer/profile';

    /**
     * @var string
     */
    public const ROUTE_CUSTOMER_ADDRESS = 'customer/address';

    /**
     * @var string
     */
    public const ROUTE_CUSTOMER_NEW_ADDRESS = 'customer/address/new';

    /**
     * @var string
     */
    public const ROUTE_CUSTOMER_UPDATE_ADDRESS = 'customer/address/update';

    /**
     * @var string
     */
    public const ROUTE_CUSTOMER_DELETE_ADDRESS = 'customer/address/delete';

    /**
     * @var string
     */
    public const ROUTE_CUSTOMER_REFRESH_ADDRESS = 'customer/address/refresh';

    /**
     * @var string
     */
    public const ROUTE_CUSTOMER_ORDER = 'customer/order';

    /**
     * @var string
     */
    public const ROUTE_CUSTOMER_ORDER_DETAILS = 'customer/order/details';

    /**
     * @var string
     */
    public const ROUTE_CUSTOMER_DELETE = 'customer/delete';

    /**
     * @var string
     */
    public const ROUTE_CUSTOMER_DELETE_CONFIRM = 'customer/delete/confirm';

    /**
     * @var string
     */
    protected const ROUTE_TOKEN = 'token';

    /**
     * @var string
     */
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
        $this->createController('/{login}', static::ROUTE_LOGIN, 'CustomerPage', 'Auth', 'login')
            ->assert('login', $this->getAllowedLocalesPattern() . 'login|login')
            ->value('login', 'login');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addLogoutRoute()
    {
        $this->createController('/{logout}', static::ROUTE_LOGOUT, 'CustomerPage', 'Auth', 'logout')
            ->assert('logout', $this->getAllowedLocalesPattern() . 'logout|logout')
            ->value('logout', 'logout');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRegisterRoute()
    {
        $this->createController('/{register}', static::ROUTE_REGISTER, 'CustomerPage', 'Register', 'index')
            ->assert('register', $this->getAllowedLocalesPattern() . 'register|register')
            ->value('register', 'register');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addForgottenPasswordRoute()
    {
        $this->createController('/{password}/forgotten', static::ROUTE_PASSWORD_FORGOTTEN, 'CustomerPage', 'Password', 'forgottenPassword')
            ->assert('password', $this->getAllowedLocalesPattern() . 'password|password')
            ->value('password', 'password');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRestorePasswordRoute()
    {
        $this->createController('/{password}/restore', static::ROUTE_PASSWORD_RESTORE, 'CustomerPage', 'Password', 'restorePassword')
            ->assert('password', $this->getAllowedLocalesPattern() . 'password|password')
            ->value('password', 'password');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerOverviewRoute()
    {
        $this->createController('/{customer}/overview', static::ROUTE_CUSTOMER_OVERVIEW, 'CustomerPage', 'Customer', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerProfileRoute()
    {
        $this->createController('/{customer}/profile', static::ROUTE_CUSTOMER_PROFILE, 'CustomerPage', 'Profile', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerAddressRoute()
    {
        $this->createController('/{customer}/address', static::ROUTE_CUSTOMER_ADDRESS, 'CustomerPage', 'Address', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addNewCustomerAddressRoute()
    {
        $this->createController('/{customer}/address/new', static::ROUTE_CUSTOMER_NEW_ADDRESS, 'CustomerPage', 'Address', 'create')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUpdateCustomerAddressRoute()
    {
        $this->createController('/{customer}/address/update', static::ROUTE_CUSTOMER_UPDATE_ADDRESS, 'CustomerPage', 'Address', 'update')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDeleteCustomerAddressRoute()
    {
        $this->createController('/{customer}/address/delete', static::ROUTE_CUSTOMER_DELETE_ADDRESS, 'CustomerPage', 'Address', 'delete')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRefreshCustomerAddressRoute()
    {
        $this->createController('/{customer}/address/refresh', static::ROUTE_CUSTOMER_REFRESH_ADDRESS, 'CustomerPage', 'Address', 'refresh')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerOrderRoute()
    {
        $this->createController('/{customer}/order', static::ROUTE_CUSTOMER_ORDER, 'CustomerPage', 'Order', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerOrderDetailsRoute()
    {
        $this->createController('/{customer}/order/details', static::ROUTE_CUSTOMER_ORDER_DETAILS, 'CustomerPage', 'Order', 'details')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerDeleteRoute()
    {
        $this->createController('/{customer}/delete', static::ROUTE_CUSTOMER_DELETE, 'CustomerPage', 'Delete', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCustomerDeleteConfirmRoute()
    {
        $this->createController('/{customer}/delete/confirm', static::ROUTE_CUSTOMER_DELETE_CONFIRM, 'CustomerPage', 'Delete', 'confirm')
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
