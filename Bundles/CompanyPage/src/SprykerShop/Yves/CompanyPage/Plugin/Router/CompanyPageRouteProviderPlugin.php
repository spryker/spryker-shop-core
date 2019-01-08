<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Plugin\Router;

use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class CompanyPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_COMPANY_LOGIN = 'company/login';
    public const ROUTE_COMPANY_REGISTER = 'company/register';
    public const ROUTE_COMPANY_OVERVIEW = 'company/overview';

    public const ROUTE_COMPANY_ADDRESS = 'company/address';
    public const ROUTE_COMPANY_ADDRESS_CREATE = 'company/address/create';
    public const ROUTE_COMPANY_ADDRESS_UPDATE = 'company/address/update';
    public const ROUTE_COMPANY_ADDRESS_DELETE = 'company/address/delete';
    public const ROUTE_COMPANY_ADDRESS_DELETE_CONFIRMATION = 'company/address/delete-confirmation';

    public const ROUTE_COMPANY_BUSINESS_UNIT = 'company/business-unit';
    public const ROUTE_COMPANY_BUSINESS_UNIT_DETAILS = 'company/business-unit/details';
    public const ROUTE_COMPANY_BUSINESS_UNIT_CREATE = 'company/business-unit/create';
    public const ROUTE_COMPANY_BUSINESS_UNIT_UPDATE = 'company/business-unit/update';
    public const ROUTE_COMPANY_BUSINESS_UNIT_DELETE = 'company/business-unit/delete';
    public const ROUTE_COMPANY_BUSINESS_UNIT_ADDRESS_CREATE = 'company/business-unit/address/create';
    public const ROUTE_COMPANY_BUSINESS_UNIT_DELETE_CONFIRMATION = 'company/business-unit/delete-confirmation';

    public const ROUTE_COMPANY_ROLE = 'company/company-role';
    public const ROUTE_COMPANY_ROLE_CREATE = 'company/company-role/create';
    public const ROUTE_COMPANY_ROLE_UPDATE = 'company/company-role/update';
    public const ROUTE_COMPANY_ROLE_DELETE = 'company/company-role/delete';
    public const ROUTE_COMPANY_ROLE_CONFIRM_DELETE = 'company/company-role/confirm-delete';
    public const ROUTE_COMPANY_ROLE_DETAILS = 'company/company-role/details';

    public const ROUTE_COMPANY_ROLE_USER_MANAGE = 'company/company-role/user/manage';
    public const ROUTE_COMPANY_ROLE_USER_ASSIGN = 'company/company-role/user/assign';
    public const ROUTE_COMPANY_ROLE_USER_UNASSIGN = 'company/company-role/user/unassign';

    public const ROUTE_COMPANY_ROLE_PERMISSION_CONFIGURE = 'company/company-role-permission/configure';
    public const ROUTE_COMPANY_ROLE_PERMISSION_ASSIGN = 'company/company-role-permission/assign';
    public const ROUTE_COMPANY_ROLE_PERMISSION_UNASSIGN = 'company/company-role-permission/unassign';

    public const ROUTE_COMPANY_USER = 'company/user';
    public const ROUTE_COMPANY_USER_CREATE = 'company/user/create';
    public const ROUTE_COMPANY_USER_UPDATE = 'company/user/update';
    public const ROUTE_COMPANY_USER_DELETE = 'company/user/delete';
    public const ROUTE_COMPANY_USER_CONFIRM_DELETE = 'company/user/confirm-delete';
    public const ROUTE_COMPANY_USER_SELECT = 'company/user/select';

    public const ROUTE_COMPANY_USER_STATUS_ENABLE = 'company/company-user-status/enable';
    public const ROUTE_COMPANY_USER_STATUS_DISABLE = 'company/company-user-status/disable';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addCompanyRoutes($routeCollection);
        $routeCollection = $this->addCompanyAddressRoutes($routeCollection);
        $routeCollection = $this->addCompanyBusinessUnitRoutes($routeCollection);
        $routeCollection = $this->addCompanyRoleRoutes($routeCollection);
        $routeCollection = $this->addPermissionRoutes($routeCollection);
        $routeCollection = $this->addCompanyUserRoutes($routeCollection);
        $routeCollection = $this->addCompanyRoleUserRoutes($routeCollection);
        $routeCollection = $this->addCompanyUserStatusRoutes($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCompanyRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(static::ROUTE_COMPANY_REGISTER, $this->buildRoute('/company/register', 'CompanyPage', 'Register', 'index'));
        $routeCollection->add(static::ROUTE_COMPANY_OVERVIEW, $this->buildRoute('/company/overview', 'CompanyPage', 'Company', 'index'));

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCompanyAddressRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(static::ROUTE_COMPANY_ADDRESS, $this->buildRoute('/company/address', 'CompanyPage', 'Address', 'index'));
        $routeCollection->add(static::ROUTE_COMPANY_ADDRESS_CREATE, $this->buildRoute('/company/address/create', 'CompanyPage', 'Address', 'create'));
        $routeCollection->add(static::ROUTE_COMPANY_ADDRESS_UPDATE, $this->buildRoute('/company/address/update', 'CompanyPage', 'Address', 'update'));
        $routeCollection->add(static::ROUTE_COMPANY_ADDRESS_DELETE, $this->buildRoute('/company/address/delete', 'CompanyPage', 'Address', 'delete'));
        $routeCollection->add(static::ROUTE_COMPANY_ADDRESS_DELETE_CONFIRMATION, $this->buildRoute('/company/address/delete-confirmation', 'CompanyPage', 'Address', 'confirmDelete'));

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCompanyBusinessUnitRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(static::ROUTE_COMPANY_BUSINESS_UNIT, $this->buildRoute('/company/business-unit', 'CompanyPage', 'BusinessUnit', 'index'));
        $routeCollection->add(static::ROUTE_COMPANY_BUSINESS_UNIT_DETAILS, $this->buildRoute('/company/business-unit/details', 'CompanyPage', 'BusinessUnit', 'details'));
        $routeCollection->add(static::ROUTE_COMPANY_BUSINESS_UNIT_CREATE, $this->buildRoute('/company/business-unit/create', 'CompanyPage', 'BusinessUnit', 'create'));
        $routeCollection->add(static::ROUTE_COMPANY_BUSINESS_UNIT_UPDATE, $this->buildRoute('/company/business-unit/update', 'CompanyPage', 'BusinessUnit', 'update'));
        $routeCollection->add(static::ROUTE_COMPANY_BUSINESS_UNIT_DELETE, $this->buildRoute('/company/business-unit/delete', 'CompanyPage', 'BusinessUnit', 'delete'));
        $routeCollection->add(static::ROUTE_COMPANY_BUSINESS_UNIT_DELETE_CONFIRMATION, $this->buildRoute('/company/business-unit/delete-confirmation', 'CompanyPage', 'BusinessUnit', 'confirmDelete'));
        $routeCollection->add(static::ROUTE_COMPANY_BUSINESS_UNIT_ADDRESS_CREATE, $this->buildRoute('/company/business-unit/address/create', 'CompanyPage', 'BusinessUnitAddress', 'create'));

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCompanyRoleRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(static::ROUTE_COMPANY_ROLE, $this->buildRoute('/company/company-role', 'CompanyPage', 'CompanyRole', 'index'));
        $routeCollection->add(static::ROUTE_COMPANY_ROLE_CREATE, $this->buildRoute('/company/company-role/create', 'CompanyPage', 'CompanyRole', 'create'));
        $routeCollection->add(static::ROUTE_COMPANY_ROLE_UPDATE, $this->buildRoute('/company/company-role/update', 'CompanyPage', 'CompanyRole', 'update'));
        $routeCollection->add(static::ROUTE_COMPANY_ROLE_DELETE, $this->buildRoute('/company/company-role/delete', 'CompanyPage', 'CompanyRole', 'delete'));
        $routeCollection->add(static::ROUTE_COMPANY_ROLE_CONFIRM_DELETE, $this->buildRoute('/company/company-role/confirm-delete', 'CompanyPage', 'CompanyRole', 'confirmDelete'));
        $routeCollection->add(static::ROUTE_COMPANY_ROLE_DETAILS, $this->buildRoute('/company/company-role/details', 'CompanyPage', 'CompanyRole', 'details'));

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addPermissionRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(static::ROUTE_COMPANY_ROLE_PERMISSION_CONFIGURE, $this->buildRoute('/company/company-role-permission/configure', 'CompanyPage', 'CompanyRolePermission', 'configure'));
        $routeCollection->add(static::ROUTE_COMPANY_ROLE_PERMISSION_ASSIGN, $this->buildRoute('/company/company-role-permission/assign', 'CompanyPage', 'CompanyRolePermission', 'assign'));
        $routeCollection->add(static::ROUTE_COMPANY_ROLE_PERMISSION_UNASSIGN, $this->buildRoute('/company/company-role-permission/unassign', 'CompanyPage', 'CompanyRolePermission', 'unassign'));

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCompanyUserRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(static::ROUTE_COMPANY_USER, $this->buildRoute('/company/user', 'CompanyPage', 'User', 'index'));
        $routeCollection->add(static::ROUTE_COMPANY_USER_CREATE, $this->buildRoute('/company/user/create', 'CompanyPage', 'User', 'create'));
        $routeCollection->add(static::ROUTE_COMPANY_USER_UPDATE, $this->buildRoute('/company/user/update', 'CompanyPage', 'User', 'update'));
        $routeCollection->add(static::ROUTE_COMPANY_USER_DELETE, $this->buildRoute('/company/user/delete', 'CompanyPage', 'User', 'delete'));
        $routeCollection->add(static::ROUTE_COMPANY_USER_CONFIRM_DELETE, $this->buildRoute('/company/user/confirm-delete', 'CompanyPage', 'User', 'confirmDelete'));
        $routeCollection->add(static::ROUTE_COMPANY_USER_SELECT, $this->buildRoute('/company/user/select', 'CompanyPage', 'BusinessOnBehalf', 'selectCompanyUser'));

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCompanyRoleUserRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(static::ROUTE_COMPANY_ROLE_USER_MANAGE, $this->buildRoute('/company/company-role/user/manage', 'CompanyPage', 'CompanyRoleUser', 'manage'));
        $routeCollection->add(static::ROUTE_COMPANY_ROLE_USER_ASSIGN, $this->buildRoute('/company/company-role/user/assign', 'CompanyPage', 'CompanyRoleUser', 'assign'));
        $routeCollection->add(static::ROUTE_COMPANY_ROLE_USER_UNASSIGN, $this->buildRoute('/company/company-role/user/unassign', 'CompanyPage', 'CompanyRoleUser', 'unassign'));

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCompanyUserStatusRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(static::ROUTE_COMPANY_USER_STATUS_ENABLE, $this->buildRoute('/company/company-user-status/enable', 'CompanyPage', 'CompanyUserStatus', 'enable'));
        $routeCollection->add(static::ROUTE_COMPANY_USER_STATUS_DISABLE, $this->buildRoute('/company/company-user-status/disable', 'CompanyPage', 'CompanyUserStatus', 'disable'));

        return $routeCollection;
    }
}
