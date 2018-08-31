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
    const ROUTE_SEARCH = 'cms_page_search';

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
        $this->createController('/{search}/cms', self::ROUTE_SEARCH, 'CmsSearchPage', 'CmsSearch', 'fulltextSearch')
            ->assert('search', $this->getAllowedLocalesPattern() . 'search|search')
            ->value('search', 'search');

        return $this;
    }
}
