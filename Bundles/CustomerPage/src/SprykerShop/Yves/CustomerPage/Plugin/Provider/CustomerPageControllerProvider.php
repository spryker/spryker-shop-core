<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Provider;

use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

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
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{login}', self::ROUTE_LOGIN, 'CustomerPage', 'Auth', 'login')
            ->assert('login', $allowedLocalesPattern . 'login|login')
            ->value('login', 'login');
        $this->createController('/{logout}', self::ROUTE_LOGOUT, 'CustomerPage', 'Auth', 'logout')
            ->assert('logout', $allowedLocalesPattern . 'logout|logout')
            ->value('logout', 'logout');
        $this->createController('/{register}', self::ROUTE_REGISTER, 'CustomerPage', 'Register', 'index')
            ->assert('register', $allowedLocalesPattern . 'register|register')
            ->value('register', 'register');

        $this->createController('/{password}/forgotten', self::ROUTE_PASSWORD_FORGOTTEN, 'CustomerPage', 'Password', 'forgottenPassword')
            ->assert('password', $allowedLocalesPattern . 'password|password')
            ->value('password', 'password');
        $this->createController('/{password}/restore', self::ROUTE_PASSWORD_RESTORE, 'CustomerPage', 'Password', 'restorePassword')
            ->assert('password', $allowedLocalesPattern . 'password|password')
            ->value('password', 'password');

        $this->createController('/{customer}/overview', self::ROUTE_CUSTOMER_OVERVIEW, 'CustomerPage', 'Customer', 'index')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer');
        $this->createController('/{customer}/profile', self::ROUTE_CUSTOMER_PROFILE, 'CustomerPage', 'Profile', 'index')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer');
        $this->createController('/{customer}/address', self::ROUTE_CUSTOMER_ADDRESS, 'CustomerPage', 'Address', 'index')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer');
        $this->createController('/{customer}/address/new', self::ROUTE_CUSTOMER_NEW_ADDRESS, 'CustomerPage', 'Address', 'create')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer');
        $this->createController('/{customer}/address/update', self::ROUTE_CUSTOMER_UPDATE_ADDRESS, 'CustomerPage', 'Address', 'update')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer');
        $this->createController('/{customer}/address/delete', self::ROUTE_CUSTOMER_DELETE_ADDRESS, 'CustomerPage', 'Address', 'delete')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer');
        $this->createController('/{customer}/address/refresh', self::ROUTE_CUSTOMER_REFRESH_ADDRESS, 'CustomerPage', 'Address', 'refresh')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer');

        $this->createController('/{customer}/order', self::ROUTE_CUSTOMER_ORDER, 'CustomerPage', 'Order', 'index')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer');
        $this->createController('/{customer}/order/details', self::ROUTE_CUSTOMER_ORDER_DETAILS, 'CustomerPage', 'Order', 'details')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer');

        $this->createController('/{customer}/delete', self::ROUTE_CUSTOMER_DELETE, 'CustomerPage', 'Delete', 'index')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer');

        $this->createController('/{customer}/delete/confirm', self::ROUTE_CUSTOMER_DELETE_CONFIRM, 'CustomerPage', 'Delete', 'confirm')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer');
    }
}
