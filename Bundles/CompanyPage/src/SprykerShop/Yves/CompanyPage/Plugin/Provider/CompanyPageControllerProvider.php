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

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
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
}
