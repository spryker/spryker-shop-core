<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleNoteWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class ConfigurableBundleNoteWidgetAsyncRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_CONFIGURABLE_BUNDLE_NOTE_ASYNC_ADD = 'configurable-bundle-note/async/add';

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
        $routeCollection = $this->addConfigurableBundleNoteAsyncAddRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundleNoteWidget\Controller\NoteAsyncController::addAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addConfigurableBundleNoteAsyncAddRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/configurable-bundle-note/async/add',
            'ConfigurableBundleNoteWidget',
            'NoteAsync',
            'addAction',
        );
        $routeCollection->add(static::ROUTE_NAME_CONFIGURABLE_BUNDLE_NOTE_ASYNC_ADD, $route);

        return $routeCollection;
    }
}
