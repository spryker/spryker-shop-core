<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserAgentWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CompanyUserAgentWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CompanyUserAgentWidget\Plugin\Router\CompanyUserAgentWidgetRouteProviderPlugin::ROUTE_NAME_COMPANY_USER_AUTOCOMPLETE} instead.
     */
    protected const ROUTE_COMPANY_USER_AUTOCOMPLETE = 'agent/company-user/autocomplete';
    public const ROUTE_NAME_COMPANY_USER_AUTOCOMPLETE = 'agent/company-user/autocomplete';

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
        $routeCollection = $this->addCompanyUserAutocompleteRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CompanyUserAgentWidget\Controller\CompanyUserAutocompleteController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCompanyUserAutocompleteRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/company-user/autocomplete', 'CompanyUserAgentWidget', 'CompanyUserAutocomplete', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_COMPANY_USER_AUTOCOMPLETE, $route);

        return $routeCollection;
    }
}
