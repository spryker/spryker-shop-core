<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use {@link \SprykerShop\Yves\AgentPage\Plugin\Router\AgentPageRouteProviderPlugin} instead.
 */
class AgentPageControllerProvider extends AbstractYvesControllerProvider
{
    /**
     * @var string
     */
    public const ROUTE_LOGIN = 'agent/login';

    /**
     * @var string
     */
    public const ROUTE_LOGOUT = 'agent/logout';

    /**
     * @var string
     */
    public const ROUTE_AGENT_OVERVIEW = 'agent/overview';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this
            ->addLoginRoute()
            ->addLogoutRoute()
            ->addOverviewRoute();
    }

    /**
     * @return $this
     */
    protected function addLoginRoute()
    {
        $this->createController('/{agent}/login', static::ROUTE_LOGIN, 'AgentPage', 'Auth', 'login')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }

    /**
     * @deprecated Use Symfony autogenerated firewall route "agent_logout" instead
     *
     * @return $this
     */
    protected function addLogoutRoute()
    {
        $this->createController('/{agent}/logout', static::ROUTE_LOGOUT, 'AgentPage', 'Auth', 'logout')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentPage\Controller\AgentController::indexAction()
     *
     * @return $this
     */
    protected function addOverviewRoute()
    {
        $this->createController('/{agent}/overview', static::ROUTE_AGENT_OVERVIEW, 'AgentPage', 'Agent', 'index')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }
}
