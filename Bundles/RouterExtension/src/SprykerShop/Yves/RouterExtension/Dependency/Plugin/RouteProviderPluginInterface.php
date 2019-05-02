<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\RouterExtension\Dependency\Plugin;

use SprykerShop\Yves\Router\Route\RouteCollection;

interface RouteProviderPluginInterface
{
    /**
     * Specification:
     * - Adds routes to the RouteCollection.
     * - Returns a RouteCollection.
     *
     * @api
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection;
}
