<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Plugin\Router;

use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class CatalogPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_SEARCH = 'search';
    public const ROUTE_SUGGESTION = 'search/suggestion';
    public const ROUTER_CHANGE_VIEW_MODE = 'change-view-mode';

    /**
     * @param RouteCollection $routeCollection
     *
     * @return RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addFulltextSearchRoute($routeCollection);
        $routeCollection = $this->addSuggestionRoute($routeCollection);
        $routeCollection = $this->addChangeViewRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param RouteCollection $routeCollection
     *
     * @return RouteCollection
     */
    protected function addFulltextSearchRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/search', 'CatalogPage', 'Catalog', 'fulltextSearch');
        $routeCollection->add(static::ROUTE_SEARCH, $route);

        return $routeCollection;
    }

    /**
     * @param RouteCollection $routeCollection
     *
     * @return RouteCollection
     */
    protected function addSuggestionRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/search/suggestion', 'CatalogPage', 'Suggestion', 'index');
        $routeCollection->add(static::ROUTE_SUGGESTION, $route);

        return $routeCollection;
    }

    /**
     * @param RouteCollection $routeCollection
     *
     * @return RouteCollection
     */
    protected function addChangeViewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/catalog/change-view-mode', 'CatalogPage', 'Catalog', 'changeViewMode');
        $routeCollection->add(static::ROUTER_CHANGE_VIEW_MODE, $route);

        return $routeCollection;
    }
}
