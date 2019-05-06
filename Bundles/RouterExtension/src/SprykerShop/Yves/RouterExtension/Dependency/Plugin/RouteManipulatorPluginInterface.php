<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\RouterExtension\Dependency\Plugin;

use SprykerShop\Yves\Router\Route\Route;

interface RouteManipulatorPluginInterface
{
    /**
     * Specification:
     * - Returns a manipulated Route.
     *
     * @api
     *
     * @param string $routeName
     * @param \Symfony\Component\Routing\Route|\SprykerShop\Yves\Router\Route\Route $route
     *
     * @return \Symfony\Component\Routing\Route|\SprykerShop\Yves\Router\Route\Route
     */
    public function manipulate(string $routeName, Route $route): Route;
}
