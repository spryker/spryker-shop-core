<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin` instead.
 */
class CompanyPageControllerProvider extends AbstractYvesControllerProvider
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

    protected const ROUTE_COMPANY_BUSINESS_UNIT_ADDRESS_UPDATE = 'company/business-unit/address/update';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addCompanyRoutes()
            ->addCompanyAddressRoutes()
            ->addCompanyBusinessUnitRoutes()
            ->addCompanyBusinessUnitAddressRoutes()
            ->addCompanyRoleRoutes()
            ->addPermissionRoutes()
            ->addCompanyUserRoutes()
            ->addCompanyRoleUserRoutes()
            ->addCompanyUserStatusRoutes();
    }

    /**
     * @return $this
     */
    protected function addCompanyRoutes()
    {
        $this->createController('/{company}/register', static::ROUTE_COMPANY_REGISTER, 'CompanyPage', 'Register', 'index')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/overview', static::ROUTE_COMPANY_OVERVIEW, 'CompanyPage', 'Company', 'index')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCompanyAddressRoutes()
    {
        $this->createController('/{company}/address', static::ROUTE_COMPANY_ADDRESS, 'CompanyPage', 'Address', 'index')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/address/create', static::ROUTE_COMPANY_ADDRESS_CREATE, 'CompanyPage', 'Address', 'create')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/address/update', static::ROUTE_COMPANY_ADDRESS_UPDATE, 'CompanyPage', 'Address', 'update')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/address/delete', static::ROUTE_COMPANY_ADDRESS_DELETE, 'CompanyPage', 'Address', 'delete')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/address/delete-confirmation', static::ROUTE_COMPANY_ADDRESS_DELETE_CONFIRMATION, 'CompanyPage', 'Address', 'confirmDelete')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCompanyBusinessUnitRoutes()
    {
        $this->createController('/{company}/business-unit', static::ROUTE_COMPANY_BUSINESS_UNIT, 'CompanyPage', 'BusinessUnit', 'index')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/business-unit/details', static::ROUTE_COMPANY_BUSINESS_UNIT_DETAILS, 'CompanyPage', 'BusinessUnit', 'details')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/business-unit/create', static::ROUTE_COMPANY_BUSINESS_UNIT_CREATE, 'CompanyPage', 'BusinessUnit', 'create')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/business-unit/update', static::ROUTE_COMPANY_BUSINESS_UNIT_UPDATE, 'CompanyPage', 'BusinessUnit', 'update')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/business-unit/delete', static::ROUTE_COMPANY_BUSINESS_UNIT_DELETE, 'CompanyPage', 'BusinessUnit', 'delete')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/business-unit/delete-confirmation', static::ROUTE_COMPANY_BUSINESS_UNIT_DELETE_CONFIRMATION, 'CompanyPage', 'BusinessUnit', 'confirmDelete')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCompanyBusinessUnitAddressRoutes()
    {
        $this->addCompanyBusinessUnitAddressCreateRoute()
            ->addCompanyBusinessUnitAddressUpdateRoute();

        return $this;
    }

    /**
     * @see \SprykerShop\Yves\CompanyPage\Controller\BusinessUnitAddressController::createAction()
     *
     * @return $this
     */
    protected function addCompanyBusinessUnitAddressCreateRoute()
    {
        $this->createController('/{company}/business-unit/address/create', static::ROUTE_COMPANY_BUSINESS_UNIT_ADDRESS_CREATE, 'CompanyPage', 'BusinessUnitAddress', 'create')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');

        return $this;
    }

    /**
     * @see \SprykerShop\Yves\CompanyPage\Controller\BusinessUnitAddressController::updateAction()
     *
     * @return $this
     */
    protected function addCompanyBusinessUnitAddressUpdateRoute()
    {
        $this->createController('/{company}/business-unit/address/update', static::ROUTE_COMPANY_BUSINESS_UNIT_ADDRESS_UPDATE, 'CompanyPage', 'BusinessUnitAddress', 'update')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCompanyRoleRoutes()
    {
        $this->createController('/{company}/company-role', static::ROUTE_COMPANY_ROLE, 'CompanyPage', 'CompanyRole', 'index')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role/create', static::ROUTE_COMPANY_ROLE_CREATE, 'CompanyPage', 'CompanyRole', 'create')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role/update', static::ROUTE_COMPANY_ROLE_UPDATE, 'CompanyPage', 'CompanyRole', 'update')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role/delete', static::ROUTE_COMPANY_ROLE_DELETE, 'CompanyPage', 'CompanyRole', 'delete')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role/confirm-delete', static::ROUTE_COMPANY_ROLE_CONFIRM_DELETE, 'CompanyPage', 'CompanyRole', 'confirmDelete')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role/details', static::ROUTE_COMPANY_ROLE_DETAILS, 'CompanyPage', 'CompanyRole', 'details')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addPermissionRoutes()
    {
        $this->createController('/{company}/company-role-permission/configure', static::ROUTE_COMPANY_ROLE_PERMISSION_CONFIGURE, 'CompanyPage', 'CompanyRolePermission', 'configure')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role-permission/assign', static::ROUTE_COMPANY_ROLE_PERMISSION_ASSIGN, 'CompanyPage', 'CompanyRolePermission', 'assign')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role-permission/unassign', static::ROUTE_COMPANY_ROLE_PERMISSION_UNASSIGN, 'CompanyPage', 'CompanyRolePermission', 'unassign')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCompanyUserRoutes()
    {
        $this->createController('/{company}/user', static::ROUTE_COMPANY_USER, 'CompanyPage', 'User', 'index')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/user/create', static::ROUTE_COMPANY_USER_CREATE, 'CompanyPage', 'User', 'create')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/user/update', static::ROUTE_COMPANY_USER_UPDATE, 'CompanyPage', 'User', 'update')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/user/delete', static::ROUTE_COMPANY_USER_DELETE, 'CompanyPage', 'User', 'delete')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/user/confirm-delete', static::ROUTE_COMPANY_USER_CONFIRM_DELETE, 'CompanyPage', 'User', 'confirmDelete')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/user/select', static::ROUTE_COMPANY_USER_SELECT, 'CompanyPage', 'BusinessOnBehalf', 'selectCompanyUser')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCompanyRoleUserRoutes()
    {
        $this->createController('/{company}/company-role/user/manage', static::ROUTE_COMPANY_ROLE_USER_MANAGE, 'CompanyPage', 'CompanyRoleUser', 'manage')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role/user/assign', static::ROUTE_COMPANY_ROLE_USER_ASSIGN, 'CompanyPage', 'CompanyRoleUser', 'assign')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role/user/unassign', static::ROUTE_COMPANY_ROLE_USER_UNASSIGN, 'CompanyPage', 'CompanyRoleUser', 'unassign')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCompanyUserStatusRoutes()
    {
        $this->createController('/{company}/company-user-status/enable', static::ROUTE_COMPANY_USER_STATUS_ENABLE, 'CompanyPage', 'CompanyUserStatus', 'enable')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-user-status/disable', static::ROUTE_COMPANY_USER_STATUS_DISABLE, 'CompanyPage', 'CompanyUserStatus', 'disable')
            ->assert('company', $this->getAllowedLocalesPattern() . 'company|company')
            ->value('company', 'company');

        return $this;
    }
}
