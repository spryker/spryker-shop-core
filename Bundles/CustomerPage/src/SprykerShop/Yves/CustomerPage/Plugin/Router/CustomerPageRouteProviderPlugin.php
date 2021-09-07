<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CustomerPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_LOGIN} instead.
     * @var string
     */
    protected const ROUTE_LOGIN = 'login';
    /**
     * @var string
     */
    public const ROUTE_NAME_LOGIN = 'login';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_LOGOUT} instead.
     * @var string
     */
    protected const ROUTE_LOGOUT = 'logout';
    /**
     * @var string
     */
    public const ROUTE_NAME_LOGOUT = 'logout';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_REGISTER} instead.
     * @var string
     */
    protected const ROUTE_REGISTER = 'register';
    /**
     * @var string
     */
    public const ROUTE_NAME_REGISTER = 'register';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_PASSWORD_FORGOTTEN} instead.
     * @var string
     */
    protected const ROUTE_PASSWORD_FORGOTTEN = 'password/forgotten';
    /**
     * @var string
     */
    public const ROUTE_NAME_PASSWORD_FORGOTTEN = 'password/forgotten';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_PASSWORD_RESTORE} instead.
     * @var string
     */
    protected const ROUTE_PASSWORD_RESTORE = 'password/restore';
    /**
     * @var string
     */
    public const ROUTE_NAME_PASSWORD_RESTORE = 'password/restore';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_OVERVIEW} instead.
     * @var string
     */
    protected const ROUTE_CUSTOMER_OVERVIEW = 'customer/overview';
    /**
     * @var string
     */
    public const ROUTE_NAME_CUSTOMER_OVERVIEW = 'customer/overview';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_PROFILE} instead.
     * @var string
     */
    protected const ROUTE_CUSTOMER_PROFILE = 'customer/profile';
    /**
     * @var string
     */
    public const ROUTE_NAME_CUSTOMER_PROFILE = 'customer/profile';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_ADDRESS} instead.
     * @var string
     */
    protected const ROUTE_CUSTOMER_ADDRESS = 'customer/address';
    /**
     * @var string
     */
    public const ROUTE_NAME_CUSTOMER_ADDRESS = 'customer/address';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_NEW_ADDRESS} instead.
     * @var string
     */
    protected const ROUTE_CUSTOMER_NEW_ADDRESS = 'customer/address/new';
    /**
     * @var string
     */
    public const ROUTE_NAME_CUSTOMER_NEW_ADDRESS = 'customer/address/new';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_UPDATE_ADDRESS} instead.
     * @var string
     */
    protected const ROUTE_CUSTOMER_UPDATE_ADDRESS = 'customer/address/update';
    /**
     * @var string
     */
    public const ROUTE_NAME_CUSTOMER_UPDATE_ADDRESS = 'customer/address/update';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_DELETE_ADDRESS} instead.
     * @var string
     */
    protected const ROUTE_CUSTOMER_DELETE_ADDRESS = 'customer/address/delete';
    /**
     * @var string
     */
    public const ROUTE_NAME_CUSTOMER_DELETE_ADDRESS = 'customer/address/delete';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_REFRESH_ADDRESS} instead.
     * @var string
     */
    protected const ROUTE_CUSTOMER_REFRESH_ADDRESS = 'customer/address/refresh';
    /**
     * @var string
     */
    public const ROUTE_NAME_CUSTOMER_REFRESH_ADDRESS = 'customer/address/refresh';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_ORDER} instead.
     * @var string
     */
    protected const ROUTE_CUSTOMER_ORDER = 'customer/order';
    /**
     * @var string
     */
    public const ROUTE_NAME_CUSTOMER_ORDER = 'customer/order';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_ORDER_DETAILS} instead.
     * @var string
     */
    protected const ROUTE_CUSTOMER_ORDER_DETAILS = 'customer/order/details';
    /**
     * @var string
     */
    public const ROUTE_NAME_CUSTOMER_ORDER_DETAILS = 'customer/order/details';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_DELETE} instead.
     * @var string
     */
    protected const ROUTE_CUSTOMER_DELETE = 'customer/delete';
    /**
     * @var string
     */
    public const ROUTE_NAME_CUSTOMER_DELETE = 'customer/delete';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_DELETE_CONFIRM} instead.
     * @var string
     */
    protected const ROUTE_CUSTOMER_DELETE_CONFIRM = 'customer/delete/confirm';
    /**
     * @var string
     */
    public const ROUTE_NAME_CUSTOMER_DELETE_CONFIRM = 'customer/delete/confirm';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_TOKEN} instead.
     * @var string
     */
    protected const ROUTE_TOKEN = 'token';
    /**
     * @var string
     */
    public const ROUTE_NAME_TOKEN = 'token';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CONFIRM_REGISTRATION} instead.
     * @var string
     */
    protected const ROUTE_CONFIRM_REGISTRATION = 'register/confirm';
    /**
     * @var string
     */
    public const ROUTE_NAME_CONFIRM_REGISTRATION = 'register/confirm';

    /**
     * @var string
     */
    protected const TOKEN_PATTERN = '[a-zA-Z0-9-_\.]+';

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
        $routeCollection = $this->addAccessTokenRoute($routeCollection);
        $routeCollection = $this->addRegistrationConfirmedRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addLoginRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/login', 'CustomerPage', 'Auth', 'loginAction');
        $routeCollection->add(static::ROUTE_NAME_LOGIN, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addLogoutRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/logout', 'CustomerPage', 'Auth', 'logoutAction');
        $routeCollection->add(static::ROUTE_NAME_LOGOUT, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRegisterRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/register', 'CustomerPage', 'Register', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_REGISTER, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addForgottenPasswordRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/password/forgotten', 'CustomerPage', 'Password', 'forgottenPasswordAction');
        $routeCollection->add(static::ROUTE_NAME_PASSWORD_FORGOTTEN, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRestorePasswordRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/password/restore', 'CustomerPage', 'Password', 'restorePasswordAction');
        $routeCollection->add(static::ROUTE_NAME_PASSWORD_RESTORE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerOverviewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/overview', 'CustomerPage', 'Customer', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_CUSTOMER_OVERVIEW, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerProfileRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/profile', 'CustomerPage', 'Profile', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_CUSTOMER_PROFILE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerAddressRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/address', 'CustomerPage', 'Address', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_CUSTOMER_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addNewCustomerAddressRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/address/new', 'CustomerPage', 'Address', 'createAction');
        $routeCollection->add(static::ROUTE_NAME_CUSTOMER_NEW_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUpdateCustomerAddressRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/address/update', 'CustomerPage', 'Address', 'updateAction');
        $routeCollection->add(static::ROUTE_NAME_CUSTOMER_UPDATE_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addDeleteCustomerAddressRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/address/delete', 'CustomerPage', 'Address', 'deleteAction');
        $routeCollection->add(static::ROUTE_NAME_CUSTOMER_DELETE_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRefreshCustomerAddressRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/address/refresh', 'CustomerPage', 'Address', 'refreshAction');
        $routeCollection->add(static::ROUTE_NAME_CUSTOMER_REFRESH_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerOrderRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/order', 'CustomerPage', 'Order', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_CUSTOMER_ORDER, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerOrderDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/order/details', 'CustomerPage', 'Order', 'detailsAction');
        $routeCollection->add(static::ROUTE_NAME_CUSTOMER_ORDER_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerDeleteRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/delete', 'CustomerPage', 'Delete', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_CUSTOMER_DELETE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerDeleteConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildPostRoute('/customer/delete/confirm', 'CustomerPage', 'Delete', 'confirmAction');
        $routeCollection->add(static::ROUTE_NAME_CUSTOMER_DELETE_CONFIRM, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Controller\AccessTokenController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAccessTokenRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/access-token/{token}', 'CustomerPage', 'AccessToken', 'indexAction');
        $route = $route->setRequirement('token', static::TOKEN_PATTERN);
        $routeCollection->add(static::ROUTE_NAME_TOKEN, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Controller\RegisterController::confirmAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRegistrationConfirmedRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/register/confirm', 'CustomerPage', 'Register', 'confirmAction');
        $routeCollection->add(static::ROUTE_NAME_CONFIRM_REGISTRATION, $route);

        return $routeCollection;
    }
}
