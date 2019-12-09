<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleNoteWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class ConfigurableBundleNoteWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_CONFIGURABLE_BUNDLE_NOTE_ADD = 'configurable-bundle-note/add';

    /**
     * {@inheritDoc}
     * - Adds configurable bundle note add action to RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addConfigurableBundleNoteAddRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundleNoteWidget\Controller\NoteController::addAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addConfigurableBundleNoteAddRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/configurable-bundle-note/add',
            'ConfigurableBundleNoteWidget',
            'Note',
            'addAction'
        );
        $routeCollection->add(static::ROUTE_CONFIGURABLE_BUNDLE_NOTE_ADD, $route);

        return $routeCollection;
    }
}
