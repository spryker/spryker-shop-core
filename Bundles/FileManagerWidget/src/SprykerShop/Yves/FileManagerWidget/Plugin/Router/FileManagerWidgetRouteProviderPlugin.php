<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class FileManagerWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_FILES_DOWNLOAD = 'files/download';

    /**
     * @var string
     */
    protected $allowedLocalesPattern;

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/files/download', 'FileManagerWidget', 'Download', 'indexAction');
        $routeCollection->add(static::ROUTE_FILES_DOWNLOAD, $route);

        return $routeCollection;
    }
}
