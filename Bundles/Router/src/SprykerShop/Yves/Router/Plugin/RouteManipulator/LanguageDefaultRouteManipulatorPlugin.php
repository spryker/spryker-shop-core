<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\Router\Plugin\RouteManipulator;

use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Routing\Route;
use SprykerShop\Yves\RouterExtension\Dependency\Plugin\RouteManipulatorPluginInterface;

/**
 * @method \SprykerShop\Yves\Router\RouterConfig getConfig()
 */
class LanguageDefaultRouteManipulatorPlugin extends AbstractPlugin implements RouteManipulatorPluginInterface
{
    /**
     * @var string
     */
    protected $allowedLocalesPattern;

    /**
     * @param string $routeName
     * @param \Symfony\Component\Routing\Route $route
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function manipulate(string $routeName, Route $route): Route
    {
        $route->setDefault('language', 'en');

        return $route;
    }
}
