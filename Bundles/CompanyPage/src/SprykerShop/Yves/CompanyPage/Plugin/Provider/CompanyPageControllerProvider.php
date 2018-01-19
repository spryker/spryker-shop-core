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
    public const ROUTE_COMPANY_CREATE = 'company/create';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{company}/create', self::ROUTE_COMPANY_CREATE, 'CompanyPage', 'Create', 'index')
            ->assert('company', $allowedLocalesPattern . 'company|company')
            ->value('company', 'company');
    }
}
