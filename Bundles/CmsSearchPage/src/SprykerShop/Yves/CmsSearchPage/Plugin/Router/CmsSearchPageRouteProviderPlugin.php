<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSearchPage\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class CmsSearchPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_SEARCH = 'search-cms';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addFulltextSearchRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addFulltextSearchRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/search/cms', 'CmsSearchPage', 'CmsSearch', 'fulltextSearchAction');
        $routeCollection->add(static::ROUTE_SEARCH, $route);

        return $routeCollection;
    }
}
