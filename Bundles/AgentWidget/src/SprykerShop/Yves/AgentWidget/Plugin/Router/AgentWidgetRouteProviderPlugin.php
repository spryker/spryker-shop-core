<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class AgentWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_CUSTOMER_AUTOCOMPLETE = 'agent-widget/customer-autocomplete';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addCustomerAutocompleteRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerAutocompleteRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent-widget/customer-autocomplete', 'AgentWidget', 'CustomerAutocomplete', 'indexAction');
        $routeCollection->add(static::ROUTE_CUSTOMER_AUTOCOMPLETE, $route);

        return $routeCollection;
    }
}
