<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CompanyPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_COMPANY_LOGIN = 'company/login';
    public const ROUTE_COMPANY_REGISTER = 'company/register';
    public const ROUTE_COMPANY_OVERVIEW = 'company/overview';

    public const ROUTE_COMPANY_ADDRESS = 'company/address';
    public const ROUTE_COMPANY_ADDRESS_CREATE = 'company/address/create';
    public const ROUTE_COMPANY_ADDRESS_UPDATE = 'company/address/update';
    public const ROUTE_COMPANY_ADDRESS_DELETE = 'company/address/delete';

    public const ROUTE_COMPANY_BUSINESS_UNIT = 'company/business-unit';
    public const ROUTE_COMPANY_BUSINESS_UNIT_DETAILS = 'company/business-unit/details';
    public const ROUTE_COMPANY_BUSINESS_UNIT_CREATE = 'company/business-unit/create';
    public const ROUTE_COMPANY_BUSINESS_UNIT_UPDATE = 'company/business-unit/update';
    public const ROUTE_COMPANY_BUSINESS_UNIT_DELETE = 'company/business-unit/delete';
    public const ROUTE_COMPANY_BUSINESS_UNIT_ADDRESS_CREATE = 'company/business-unit/address/create';

    public const ROUTE_COMPANY_ROLE = 'company/company-role';
    public const ROUTE_COMPANY_ROLE_CREATE = 'company/company-role/create';
    public const ROUTE_COMPANY_ROLE_UPDATE = 'company/company-role/update';
    public const ROUTE_COMPANY_ROLE_DELETE = 'company/company-role/delete';
    public const ROUTE_COMPANY_ROLE_DETAILS = 'company/company-role/details';

    public const ROUTE_COMPANY_ROLE_USER_MANAGE = 'company/company-role/user/manage';
    public const ROUTE_COMPANY_ROLE_USER_ASSIGN = 'company/company-role/user/assign';
    public const ROUTE_COMPANY_ROLE_USER_UNASSIGN = 'company/company-role/user/unassign';

    public const ROUTE_PERMISSION_CONFIGURE = 'company/permission/configure';
    public const ROUTE_PERMISSION_MANAGE = 'company/permission/manage';
    public const ROUTE_PERMISSION_ASSIGN = 'company/permission/assign';
    public const ROUTE_PERMISSION_UNASSIGN = 'company/permission/unassign';

    public const ROUTE_COMPANY_USER = 'company/user';
    public const ROUTE_COMPANY_USER_CREATE = 'company/user/create';
    public const ROUTE_COMPANY_USER_UPDATE = 'company/user/update';
    public const ROUTE_COMPANY_USER_DELETE = 'company/user/delete';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->createCompanyControllers();
        $this->createCompanyAddressControllers();
        $this->createCompanyBusinessUnitControllers();
        $this->createCompanyRoleControllers();
        $this->createPermissionControllers();
        $this->createCompanyUserControllers();
        $this->createCompanyRoleUserControllers();
    }

    /**
     * @return void
     */
    protected function createCompanyControllers(): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{company}/login', self::ROUTE_COMPANY_LOGIN, 'CompanyPage', 'Auth', 'login')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/register', self::ROUTE_COMPANY_REGISTER, 'CompanyPage', 'Register', 'index')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/overview', self::ROUTE_COMPANY_OVERVIEW, 'CompanyPage', 'Company', 'index')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
    }

    /**
     * @return void
     */
    protected function createCompanyAddressControllers(): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{company}/address', self::ROUTE_COMPANY_ADDRESS, 'CompanyPage', 'Address', 'index')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/address/create', self::ROUTE_COMPANY_ADDRESS_CREATE, 'CompanyPage', 'Address', 'create')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/address/update', self::ROUTE_COMPANY_ADDRESS_UPDATE, 'CompanyPage', 'Address', 'update')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/address/delete', self::ROUTE_COMPANY_ADDRESS_DELETE, 'CompanyPage', 'Address', 'delete')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
    }

    /**
     * @return void
     */
    protected function createCompanyBusinessUnitControllers(): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{company}/business-unit', self::ROUTE_COMPANY_BUSINESS_UNIT, 'CompanyPage', 'BusinessUnit', 'index')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/business-unit/details', self::ROUTE_COMPANY_BUSINESS_UNIT_DETAILS, 'CompanyPage', 'BusinessUnit', 'details')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/business-unit/create', self::ROUTE_COMPANY_BUSINESS_UNIT_CREATE, 'CompanyPage', 'BusinessUnit', 'create')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/business-unit/update', self::ROUTE_COMPANY_BUSINESS_UNIT_UPDATE, 'CompanyPage', 'BusinessUnit', 'update')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/business-unit/delete', self::ROUTE_COMPANY_BUSINESS_UNIT_DELETE, 'CompanyPage', 'BusinessUnit', 'delete')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/business-unit/address/create', self::ROUTE_COMPANY_BUSINESS_UNIT_ADDRESS_CREATE, 'CompanyPage', 'BusinessUnitAddress', 'create')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
    }

    /**
     * @return void
     */
    protected function createCompanyRoleControllers(): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{company}/company-role', self::ROUTE_COMPANY_ROLE, 'CompanyPage', 'CompanyRole', 'index')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role/create', self::ROUTE_COMPANY_ROLE_CREATE, 'CompanyPage', 'CompanyRole', 'create')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role/update', self::ROUTE_COMPANY_ROLE_UPDATE, 'CompanyPage', 'CompanyRole', 'update')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role/delete', self::ROUTE_COMPANY_ROLE_DELETE, 'CompanyPage', 'CompanyRole', 'delete')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role/details', self::ROUTE_COMPANY_ROLE_DETAILS, 'CompanyPage', 'CompanyRole', 'details')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
    }

    /**
     * @return void
     */
    protected function createPermissionControllers(): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{company}/permission/configure', self::ROUTE_PERMISSION_CONFIGURE, 'CompanyPage', 'Permission', 'configure')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/permission/manage', self::ROUTE_PERMISSION_MANAGE, 'CompanyPage', 'Permission', 'manage')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/permission/assign', self::ROUTE_PERMISSION_ASSIGN, 'CompanyPage', 'Permission', 'assign')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/permission/unassign', self::ROUTE_PERMISSION_UNASSIGN, 'CompanyPage', 'Permission', 'unassign')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
    }

    /**
     * @return void
     */
    protected function createCompanyUserControllers(): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{company}/user', self::ROUTE_COMPANY_USER, 'CompanyPage', 'User', 'index')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/user/create', self::ROUTE_COMPANY_USER_CREATE, 'CompanyPage', 'User', 'create')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/user/update', self::ROUTE_COMPANY_USER_UPDATE, 'CompanyPage', 'User', 'update')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/user/delete', self::ROUTE_COMPANY_USER_DELETE, 'CompanyPage', 'User', 'delete')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
    }

    /**
     * @return void
     */
    protected function createCompanyRoleUserControllers(): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{company}/company-role/user/manage', self::ROUTE_COMPANY_ROLE_USER_MANAGE, 'CompanyPage', 'CompanyRoleUser', 'manage')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role/user/assign', self::ROUTE_COMPANY_ROLE_USER_ASSIGN, 'CompanyPage', 'CompanyRoleUser', 'assign')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
        $this->createController('/{company}/company-role/user/unassign', self::ROUTE_COMPANY_ROLE_USER_UNASSIGN, 'CompanyPage', 'CompanyRoleUser', 'unassign')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
    }
}
