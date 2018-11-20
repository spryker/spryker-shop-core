<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class AgentPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_LOGIN = 'agent/login';
    public const ROUTE_LOGOUT = 'agent/logout';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this
            ->addLoginRoute();
    }

    /**
     * @return $this
     */
    protected function addLoginRoute(): self
    {
        $this->createController('/{agent}/login', static::ROUTE_LOGIN, 'AgentPage', 'Auth', 'login')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }
}
