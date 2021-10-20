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
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CatalogPage\Plugin\Router\CatalogPageRouteProviderPlugin::ROUTE_NAME_SEARCH} instead.
     * @var string
     */
    protected const ROUTE_SEARCH = 'search';

    /**
     * @var string
     */
    public const ROUTE_NAME_SEARCH = 'search';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CatalogPage\Plugin\Router\CatalogPageRouteProviderPlugin::ROUTE_NAME_SUGGESTION} instead.
     * @var string
     */
    protected const ROUTE_SUGGESTION = 'search/suggestion';

    /**
     * @var string
     */
    public const ROUTE_NAME_SUGGESTION = 'search/suggestion';

    /**
     * @var string
     */
    protected const ROUTER_CHANGE_VIEW_MODE = 'change-view-mode';

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
        $routeCollection->add(static::ROUTE_NAME_SEARCH, $route);

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
        $routeCollection->add(static::ROUTE_NAME_SUGGESTION, $route);

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
}
