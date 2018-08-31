<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSearchPage;

use Spryker\Client\CmsPageSearch\CmsPageSearchClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CatalogPage\CmsSearchPageDependencyProvider;

/**
 * @method \SprykerShop\Yves\CmsSearchPage\CmsSearchPageConfig getConfig()
 */
class CmsSearchPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CmsSearchPage\Dependency\Client\CmsSearchPageToCmsPageSearchClientInterface
     */
    public function getCmsPageSearchClient(): CmsPageSearchClientInterface
    {
        return $this->getProvidedDependency(CmsSearchPageDependencyProvider::CLIENT_CMS_SEARCH_PAGE);
    }

    /**
     * @return string[]
     */
    public function getCmsSearchPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CmsSearchPageDependencyProvider::PLUGIN_CMS_SEARCH_PAGE_WIDGETS);
    }
}
