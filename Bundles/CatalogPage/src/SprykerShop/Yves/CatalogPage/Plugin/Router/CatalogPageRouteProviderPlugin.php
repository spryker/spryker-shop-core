<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CatalogPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_SEARCH = 'search';
    protected const ROUTE_SUGGESTION = 'search/suggestion';
    protected const ROUTER_CHANGE_VIEW_MODE = 'change-view-mode';
    protected const ROUTE_URL_PURIFY = 'search/url-purify';

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
        $routeCollection = $this->addFulltextSearchRoute($routeCollection);
        $routeCollection = $this->addSuggestionRoute($routeCollection);
        $routeCollection = $this->addChangeViewRoute($routeCollection);
        $routeCollection = $this->addUrlPurifyRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addFulltextSearchRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/search', 'CatalogPage', 'Catalog', 'fulltextSearchAction');
        $routeCollection->add(static::ROUTE_SEARCH, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSuggestionRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/search/suggestion', 'CatalogPage', 'Suggestion', 'indexAction');
        $routeCollection->add(static::ROUTE_SUGGESTION, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addChangeViewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/catalog/change-view-mode', 'CatalogPage', 'Catalog', 'changeViewModeAction');
        $routeCollection->add(static::ROUTER_CHANGE_VIEW_MODE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUrlPurifyRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/search/url-purify', 'CatalogPage', 'UrlPurify', 'index');
        $routeCollection->add(static::ROUTE_URL_PURIFY, $route);

        return $routeCollection;
    }
}
