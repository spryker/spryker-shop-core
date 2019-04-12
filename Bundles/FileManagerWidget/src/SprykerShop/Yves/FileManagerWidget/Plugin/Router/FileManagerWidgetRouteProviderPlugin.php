<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget\Plugin\Router;

use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class FileManagerWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_FILES_DOWNLOAD = 'files/download';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/files/download', 'FileManagerWidget', 'Download');

        $routeCollection->add(static::ROUTE_FILES_DOWNLOAD, $route);

        return $routeCollection;
    }
}
