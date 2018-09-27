<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSearchPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CmsSearchPage\Dependency\Client\CmsSearchPageToCmsPageSearchClientInterface;

class CmsSearchPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CmsSearchPage\Dependency\Client\CmsSearchPageToCmsPageSearchClientInterface
     */
    public function getCmsPageSearchClient(): CmsSearchPageToCmsPageSearchClientInterface
    {
        return $this->getProvidedDependency(CmsSearchPageDependencyProvider::CLIENT_CMS_SEARCH_PAGE);
    }
}
