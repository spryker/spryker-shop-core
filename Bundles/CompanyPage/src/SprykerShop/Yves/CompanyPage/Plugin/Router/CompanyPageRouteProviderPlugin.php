<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

class CompanyPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_LOGIN} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_LOGIN = 'company/login';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_LOGIN = 'company/login';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_REGISTER} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_REGISTER = 'company/register';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_REGISTER = 'company/register';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_OVERVIEW} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_OVERVIEW = 'company/overview';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_OVERVIEW = 'company/overview';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ADDRESS} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ADDRESS = 'company/address';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ADDRESS = 'company/address';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ADDRESS_CREATE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ADDRESS_CREATE = 'company/address/create';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ADDRESS_CREATE = 'company/address/create';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ADDRESS_UPDATE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ADDRESS_UPDATE = 'company/address/update';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ADDRESS_UPDATE = 'company/address/update';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ADDRESS_DELETE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ADDRESS_DELETE = 'company/address/delete';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ADDRESS_DELETE = 'company/address/delete';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ADDRESS_DELETE_CONFIRMATION} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ADDRESS_DELETE_CONFIRMATION = 'company/address/delete-confirmation';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ADDRESS_DELETE_CONFIRMATION = 'company/address/delete-confirmation';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_BUSINESS_UNIT} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_BUSINESS_UNIT = 'company/business-unit';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_BUSINESS_UNIT = 'company/business-unit';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_BUSINESS_UNIT_DETAILS} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_BUSINESS_UNIT_DETAILS = 'company/business-unit/details';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_BUSINESS_UNIT_DETAILS = 'company/business-unit/details';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_BUSINESS_UNIT_CREATE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_BUSINESS_UNIT_CREATE = 'company/business-unit/create';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_BUSINESS_UNIT_CREATE = 'company/business-unit/create';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_BUSINESS_UNIT_UPDATE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_BUSINESS_UNIT_UPDATE = 'company/business-unit/update';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_BUSINESS_UNIT_UPDATE = 'company/business-unit/update';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_BUSINESS_UNIT_DELETE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_BUSINESS_UNIT_DELETE = 'company/business-unit/delete';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_BUSINESS_UNIT_DELETE = 'company/business-unit/delete';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_BUSINESS_UNIT_ADDRESS_CREATE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_BUSINESS_UNIT_ADDRESS_CREATE = 'company/business-unit/address/create';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_BUSINESS_UNIT_ADDRESS_CREATE = 'company/business-unit/address/create';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_BUSINESS_UNIT_DELETE_CONFIRMATION} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_BUSINESS_UNIT_DELETE_CONFIRMATION = 'company/business-unit/delete-confirmation';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_BUSINESS_UNIT_DELETE_CONFIRMATION = 'company/business-unit/delete-confirmation';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ROLE = 'company/company-role';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ROLE = 'company/company-role';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_CREATE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ROLE_CREATE = 'company/company-role/create';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ROLE_CREATE = 'company/company-role/create';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_UPDATE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ROLE_UPDATE = 'company/company-role/update';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ROLE_UPDATE = 'company/company-role/update';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_DELETE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ROLE_DELETE = 'company/company-role/delete';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ROLE_DELETE = 'company/company-role/delete';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_CONFIRM_DELETE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ROLE_CONFIRM_DELETE = 'company/company-role/confirm-delete';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ROLE_CONFIRM_DELETE = 'company/company-role/confirm-delete';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_DETAILS} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ROLE_DETAILS = 'company/company-role/details';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ROLE_DETAILS = 'company/company-role/details';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_USER_MANAGE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ROLE_USER_MANAGE = 'company/company-role/user/manage';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ROLE_USER_MANAGE = 'company/company-role/user/manage';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_USER_ASSIGN} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ROLE_USER_ASSIGN = 'company/company-role/user/assign';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ROLE_USER_ASSIGN = 'company/company-role/user/assign';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_USER_UNASSIGN} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ROLE_USER_UNASSIGN = 'company/company-role/user/unassign';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ROLE_USER_UNASSIGN = 'company/company-role/user/unassign';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_PERMISSION_CONFIGURE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ROLE_PERMISSION_CONFIGURE = 'company/company-role-permission/configure';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ROLE_PERMISSION_CONFIGURE = 'company/company-role-permission/configure';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_PERMISSION_ASSIGN} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ROLE_PERMISSION_ASSIGN = 'company/company-role-permission/assign';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ROLE_PERMISSION_ASSIGN = 'company/company-role-permission/assign';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_PERMISSION_UNASSIGN} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_ROLE_PERMISSION_UNASSIGN = 'company/company-role-permission/unassign';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_ROLE_PERMISSION_UNASSIGN = 'company/company-role-permission/unassign';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_USER} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_USER = 'company/user';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_USER = 'company/user';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_USER_CREATE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_USER_CREATE = 'company/user/create';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_USER_CREATE = 'company/user/create';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_USER_UPDATE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_USER_UPDATE = 'company/user/update';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_USER_UPDATE = 'company/user/update';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_USER_DELETE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_USER_DELETE = 'company/user/delete';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_USER_DELETE = 'company/user/delete';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_USER_CONFIRM_DELETE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_USER_CONFIRM_DELETE = 'company/user/confirm-delete';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_USER_CONFIRM_DELETE = 'company/user/confirm-delete';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_USER_SELECT} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_USER_SELECT = 'company/user/select';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_USER_SELECT = 'company/user/select';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_USER_STATUS_ENABLE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_USER_STATUS_ENABLE = 'company/company-user-status/enable';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_USER_STATUS_ENABLE = 'company/company-user-status/enable';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_USER_STATUS_DISABLE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_USER_STATUS_DISABLE = 'company/company-user-status/disable';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_USER_STATUS_DISABLE = 'company/company-user-status/disable';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_BUSINESS_UNIT_ADDRESS_UPDATE} instead.
     * @var string
     */
    protected const ROUTE_COMPANY_BUSINESS_UNIT_ADDRESS_UPDATE = 'company/business-unit/address/update';
    /**
     * @var string
     */
    public const ROUTE_NAME_COMPANY_BUSINESS_UNIT_ADDRESS_UPDATE = 'company/business-unit/address/update';

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
        $routeCollection = $this->addCompanyRoutes($routeCollection);
        $routeCollection = $this->addCompanyAddressRoutes($routeCollection);
        $routeCollection = $this->addCompanyBusinessUnitRoutes($routeCollection);
        $routeCollection = $this->addCompanyBusinessUnitAddressRoutes($routeCollection);
        $routeCollection = $this->addCompanyRoleRoutes($routeCollection);
        $routeCollection = $this->addPermissionRoutes($routeCollection);
        $routeCollection = $this->addCompanyUserRoutes($routeCollection);
        $routeCollection = $this->addCompanyRoleUserRoutes($routeCollection);
        $routeCollection = $this->addCompanyUserStatusRoutes($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCompanyRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/register', 'CompanyPage', 'Register', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_REGISTER, $route);
        $route = $this->buildRoute('/company/overview', 'CompanyPage', 'Company', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_OVERVIEW, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCompanyAddressRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/address', 'CompanyPage', 'Address', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ADDRESS, $route);
        $route = $this->buildRoute('/company/address/create', 'CompanyPage', 'Address', 'createAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ADDRESS_CREATE, $route);
        $route = $this->buildRoute('/company/address/update', 'CompanyPage', 'Address', 'updateAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ADDRESS_UPDATE, $route);
        $route = $this->buildRoute('/company/address/delete', 'CompanyPage', 'Address', 'deleteAction');
        $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ADDRESS_DELETE, $route);
        $route = $this->buildRoute('/company/address/delete-confirmation', 'CompanyPage', 'Address', 'confirmDeleteAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ADDRESS_DELETE_CONFIRMATION, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCompanyBusinessUnitRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/business-unit', 'CompanyPage', 'BusinessUnit', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_BUSINESS_UNIT, $route);
        $route = $this->buildRoute('/company/business-unit/details', 'CompanyPage', 'BusinessUnit', 'detailsAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_BUSINESS_UNIT_DETAILS, $route);
        $route = $this->buildRoute('/company/business-unit/create', 'CompanyPage', 'BusinessUnit', 'createAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_BUSINESS_UNIT_CREATE, $route);
        $route = $this->buildRoute('/company/business-unit/update', 'CompanyPage', 'BusinessUnit', 'updateAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_BUSINESS_UNIT_UPDATE, $route);
        $route = $this->buildRoute('/company/business-unit/delete', 'CompanyPage', 'BusinessUnit', 'deleteAction');
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_COMPANY_BUSINESS_UNIT_DELETE, $route);
        $route = $this->buildRoute('/company/business-unit/delete-confirmation', 'CompanyPage', 'BusinessUnit', 'confirmDeleteAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_BUSINESS_UNIT_DELETE_CONFIRMATION, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCompanyBusinessUnitAddressRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addCompanyBusinessUnitAddressCreateRoute($routeCollection);
        $routeCollection = $this->addCompanyBusinessUnitAddressUpdateRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCompanyBusinessUnitAddressCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/business-unit/address/create', 'CompanyPage', 'BusinessUnitAddress', 'createAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_BUSINESS_UNIT_ADDRESS_CREATE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCompanyBusinessUnitAddressUpdateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/business-unit/address/update', 'CompanyPage', 'BusinessUnitAddress', 'updateAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_BUSINESS_UNIT_ADDRESS_UPDATE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCompanyRoleRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/company-role', 'CompanyPage', 'CompanyRole', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ROLE, $route);
        $route = $this->buildRoute('/company/company-role/create', 'CompanyPage', 'CompanyRole', 'createAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ROLE_CREATE, $route);
        $route = $this->buildRoute('/company/company-role/update', 'CompanyPage', 'CompanyRole', 'updateAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ROLE_UPDATE, $route);
        $route = $this->buildPostRoute('/company/company-role/delete', 'CompanyPage', 'CompanyRole', 'deleteAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ROLE_DELETE, $route);
        $route = $this->buildRoute('/company/company-role/confirm-delete', 'CompanyPage', 'CompanyRole', 'confirmDeleteAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ROLE_CONFIRM_DELETE, $route);
        $route = $this->buildRoute('/company/company-role/details', 'CompanyPage', 'CompanyRole', 'detailsAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ROLE_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPermissionRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/company-role-permission/configure', 'CompanyPage', 'CompanyRolePermission', 'configureAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ROLE_PERMISSION_CONFIGURE, $route);
        $route = $this->buildRoute('/company/company-role-permission/assign', 'CompanyPage', 'CompanyRolePermission', 'assignAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ROLE_PERMISSION_ASSIGN, $route);
        $route = $this->buildRoute('/company/company-role-permission/unassign', 'CompanyPage', 'CompanyRolePermission', 'unassignAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ROLE_PERMISSION_UNASSIGN, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCompanyUserRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/user', 'CompanyPage', 'User', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_USER, $route);
        $route = $this->buildRoute('/company/user/create', 'CompanyPage', 'User', 'createAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_USER_CREATE, $route);
        $route = $this->buildRoute('/company/user/update', 'CompanyPage', 'User', 'updateAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_USER_UPDATE, $route);
        $route = $this->buildPostRoute('/company/user/delete', 'CompanyPage', 'User', 'deleteAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_USER_DELETE, $route);
        $route = $this->buildRoute('/company/user/confirm-delete', 'CompanyPage', 'User', 'confirmDeleteAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_USER_CONFIRM_DELETE, $route);
        $route = $this->buildRoute('/company/user/select', 'CompanyPage', 'BusinessOnBehalf', 'selectCompanyUserAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_USER_SELECT, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCompanyRoleUserRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/company-role/user/manage', 'CompanyPage', 'CompanyRoleUser', 'manageAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ROLE_USER_MANAGE, $route);
        $route = $this->buildRoute('/company/company-role/user/assign', 'CompanyPage', 'CompanyRoleUser', 'assignAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ROLE_USER_ASSIGN, $route);
        $route = $this->buildRoute('/company/company-role/user/unassign', 'CompanyPage', 'CompanyRoleUser', 'unassignAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_ROLE_USER_UNASSIGN, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCompanyUserStatusRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/company-user-status/enable', 'CompanyPage', 'CompanyUserStatus', 'enableAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_USER_STATUS_ENABLE, $route);
        $route = $this->buildRoute('/company/company-user-status/disable', 'CompanyPage', 'CompanyUserStatus', 'disableAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_USER_STATUS_DISABLE, $route);

        return $routeCollection;
    }
}
