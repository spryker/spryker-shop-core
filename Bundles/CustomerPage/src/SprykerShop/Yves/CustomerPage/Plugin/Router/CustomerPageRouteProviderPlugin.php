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
    protected const ROUTE_LOGIN = 'login';
    protected const ROUTE_LOGOUT = 'logout';
    protected const ROUTE_REGISTER = 'register';
    protected const ROUTE_PASSWORD_FORGOTTEN = 'password/forgotten';
    protected const ROUTE_PASSWORD_RESTORE = 'password/restore';
    protected const ROUTE_CUSTOMER_OVERVIEW = 'customer/overview';
    protected const ROUTE_CUSTOMER_PROFILE = 'customer/profile';
    protected const ROUTE_CUSTOMER_ADDRESS = 'customer/address';
    protected const ROUTE_CUSTOMER_NEW_ADDRESS = 'customer/address/new';
    protected const ROUTE_CUSTOMER_UPDATE_ADDRESS = 'customer/address/update';
    protected const ROUTE_CUSTOMER_DELETE_ADDRESS = 'customer/address/delete';
    protected const ROUTE_CUSTOMER_REFRESH_ADDRESS = 'customer/address/refresh';
    protected const ROUTE_CUSTOMER_ORDER = 'customer/order';
    protected const ROUTE_CUSTOMER_ORDER_DETAILS = 'customer/order/details';
    protected const ROUTE_CUSTOMER_DELETE = 'customer/delete';
    protected const ROUTE_CUSTOMER_DELETE_CONFIRM = 'customer/delete/confirm';
    protected const ROUTE_CUSTOMER_RETURN = 'customer/return';
    protected const ROUTE_TOKEN = 'token';

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
        $routeCollection = $this->addCustomerReturnRoute($routeCollection);

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
        $routeCollection->add(static::ROUTE_LOGIN, $route);

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
        $routeCollection->add(static::ROUTE_LOGOUT, $route);

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
        $routeCollection->add(static::ROUTE_REGISTER, $route);

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
        $routeCollection->add(static::ROUTE_PASSWORD_FORGOTTEN, $route);

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
        $routeCollection->add(static::ROUTE_PASSWORD_RESTORE, $route);

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
        $routeCollection->add(static::ROUTE_CUSTOMER_OVERVIEW, $route);

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
        $routeCollection->add(static::ROUTE_CUSTOMER_PROFILE, $route);

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
        $routeCollection->add(static::ROUTE_CUSTOMER_ADDRESS, $route);

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
        $routeCollection->add(static::ROUTE_CUSTOMER_NEW_ADDRESS, $route);

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
        $routeCollection->add(static::ROUTE_CUSTOMER_UPDATE_ADDRESS, $route);

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
        $routeCollection->add(static::ROUTE_CUSTOMER_DELETE_ADDRESS, $route);

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
        $routeCollection->add(static::ROUTE_CUSTOMER_REFRESH_ADDRESS, $route);

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
        $routeCollection->add(static::ROUTE_CUSTOMER_ORDER, $route);

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
        $routeCollection->add(static::ROUTE_CUSTOMER_ORDER_DETAILS, $route);

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
        $routeCollection->add(static::ROUTE_CUSTOMER_DELETE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerDeleteConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/delete/confirm', 'CustomerPage', 'Delete', 'confirmAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_DELETE_CONFIRM, $route);

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
        $routeCollection->add(static::ROUTE_TOKEN, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerReturnRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/return', 'CustomerPage', 'Return', 'indexAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_RETURN, $route);

        return $routeCollection;
    }
}
