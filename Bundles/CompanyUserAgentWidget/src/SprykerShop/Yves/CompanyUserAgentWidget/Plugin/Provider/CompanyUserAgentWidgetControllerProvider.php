<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserAgentWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CompanyUserAgentWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_COMPANY_USER_AGENT_AUTOCOMPLETE = 'agent/company-user/autocomplete';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addCompanyUserAgentAutocompleteRoute();
    }

    /**
     * @uses \SprykerShop\Yves\CompanyUserAgentWidget\Controller\CompanyUserAgentViewController::indexAction()
     *
     * @return $this
     */
    protected function addCompanyUserAgentAutocompleteRoute()
    {
        $this->createController('/{agent}/company-user/autocomplete', static::ROUTE_COMPANY_USER_AGENT_AUTOCOMPLETE, 'CompanyUserAgentWidget', 'CompanyUserAgentAutocomplete')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }
}
