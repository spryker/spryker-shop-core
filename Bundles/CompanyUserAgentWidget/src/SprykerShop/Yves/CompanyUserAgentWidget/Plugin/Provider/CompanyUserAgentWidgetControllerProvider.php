<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserAgentWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use {@link \SprykerShop\Yves\CompanyUserAgentWidget\Plugin\Router\CompanyUserAgentWidgetRouteProviderPlugin} instead.
 */
class CompanyUserAgentWidgetControllerProvider extends AbstractYvesControllerProvider
{
    /**
     * @var string
     */
    protected const ROUTE_COMPANY_USER_AUTOCOMPLETE = 'agent/company-user/autocomplete';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addCompanyUserAutocompleteRoute();
    }

    /**
     * @uses \SprykerShop\Yves\CompanyUserAgentWidget\Controller\CompanyUserAutocompleteController::indexAction()
     *
     * @return $this
     */
    protected function addCompanyUserAutocompleteRoute()
    {
        $this->createController('/{agent}/company-user/autocomplete', static::ROUTE_COMPANY_USER_AUTOCOMPLETE, 'CompanyUserAgentWidget', 'CompanyUserAutocomplete')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }
}
