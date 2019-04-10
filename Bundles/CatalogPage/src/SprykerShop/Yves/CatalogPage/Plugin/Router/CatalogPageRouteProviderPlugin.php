<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CatalogPageRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
{
    public const ROUTE_SEARCH = 'search';
    public const ROUTE_SUGGESTION = 'search/suggestion';
    public const ROUTER_CHANGE_VIEW_MODE = 'change-view-mode';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $routeCollection = $this->addFulltextSearchRoute($routeCollection);
        $routeCollection = $this->addSuggestionRoute($routeCollection);
        $routeCollection = $this->addChangeViewRoute($routeCollection);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addFulltextSearchRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/search', 'CatalogPage', 'Catalog', 'fulltextSearchAction');
        $routeCollection->add(static::ROUTE_SEARCH, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addSuggestionRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/search/suggestion', 'CatalogPage', 'Suggestion', 'indexAction');
        $routeCollection->add(static::ROUTE_SUGGESTION, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addChangeViewRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/catalog/change-view-mode', 'CatalogPage', 'Catalog', 'changeViewModeAction');
        $routeCollection->add(static::ROUTER_CHANGE_VIEW_MODE, $route);
        return $routeCollection;
    }
}
