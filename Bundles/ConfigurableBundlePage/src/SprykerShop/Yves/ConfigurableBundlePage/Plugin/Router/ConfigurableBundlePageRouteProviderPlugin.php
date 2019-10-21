<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class ConfigurableBundlePageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_CONFIGURATOR_TEMPLATE_SELECTION = 'configurable-bundle/configurator/template-selection';

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
        $routeCollection = $this->addConfiguratorTemplateSelectionRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundlePage\Controller\ConfiguratorController::templateSelectionAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addConfiguratorTemplateSelectionRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/configurable-bundle/configurator/template-selection', 'ConfigurableBundlePage', 'Configurator', 'templateSelectionAction');
        $routeCollection->add(static::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION, $route);

        return $routeCollection;
    }
}
