<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\AgentWidget\Plugin\Router\AgentWidgetRouteProviderPlugin` instead.
 */
class AgentWidgetControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_CUSTOMER_AUTOCOMPLETE = 'agent-widget/customer-autocomplete';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addCustomerAutocompleteRoute();
    }

    /**
     * @return $this
     */
    protected function addCustomerAutocompleteRoute()
    {
        $this->createController(
            '/{agentWidget}/customer-autocomplete',
            self::ROUTE_CUSTOMER_AUTOCOMPLETE,
            'AgentWidget',
            'CustomerAutocomplete',
            'index'
        )
            ->assert('agentWidget', $this->getAllowedLocalesPattern() . 'agent-widget|agent-widget')
            ->value('agentWidget', 'agent-widget');

        return $this;
    }
}
