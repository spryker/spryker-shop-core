<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CompanyUserPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_COMPANY_USER_CHANGE = 'company-user/change';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->createCompanyUserControllers();
    }

    /**
     * @return void
     */
    protected function createCompanyUserControllers(): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{company-user}/change', static::ROUTE_COMPANY_USER_CHANGE, 'CompanyUserPage', 'CompanyUser', 'change')
            ->assert('company-user', $allowedLocalesPattern . 'company-user|company-user')
            ->value('company-user', 'company-user');
    }
}
