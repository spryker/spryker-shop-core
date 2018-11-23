<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSearchPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CmsSearchPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_SEARCH = 'search-cms';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addFulltextSearchRoute();
    }

    /**
     * @return $this
     */
    protected function addFulltextSearchRoute(): self
    {
        $this->createController('/{search}/cms', static::ROUTE_SEARCH, 'CmsSearchPage', 'CmsSearch', 'fulltextSearch')
            ->assert('search', $this->getAllowedLocalesPattern() . 'search|search')
            ->value('search', 'search');

        return $this;
    }
}
