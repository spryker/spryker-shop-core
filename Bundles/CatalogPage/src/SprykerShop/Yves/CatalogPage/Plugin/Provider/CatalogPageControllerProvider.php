<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CatalogPageControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_SEARCH = 'search';
    const ROUTE_SUGGESTION = 'search/suggestion';
    const ROUTER_CHANGE_VIEW_MODE = 'change-view-mode';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{search}', self::ROUTE_SEARCH, 'CatalogPage', 'Catalog', 'fulltextSearch')
            ->assert('search', $allowedLocalesPattern . 'search|search')
            ->value('search', 'search');

        $this->createController('/{search}/suggestion', self::ROUTE_SUGGESTION, 'CatalogPage', 'Suggestion', 'index')
            ->assert('search', $allowedLocalesPattern . 'search|search')
            ->value('search', 'search');

        $this->createController('/{catalog}/change-view-mode', static::ROUTER_CHANGE_VIEW_MODE, 'CatalogPage', 'Catalog', 'changeViewMode')
            ->assert('catalog', $allowedLocalesPattern . 'catalog|catalog')
            ->value('catalog', 'catalog');
    }
}
