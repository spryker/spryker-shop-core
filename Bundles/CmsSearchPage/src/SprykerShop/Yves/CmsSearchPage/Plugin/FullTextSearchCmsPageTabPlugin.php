<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSearchPage\Plugin;

use Generated\Shared\Transfer\TabMetaDataTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CmsSearchPage\Plugin\Router\CmsSearchPageRouteProviderPlugin;
use SprykerShop\Yves\TabsWidgetExtension\Plugin\FullTextSearchTabPluginInterface;

/**
 * @method \SprykerShop\Yves\CmsSearchPage\CmsSearchPageFactory getFactory()
 */
class FullTextSearchCmsPageTabPlugin extends AbstractPlugin implements FullTextSearchTabPluginInterface
{
    /**
     * @var string
     */
    protected const NAME = 'FullTextSearchCmsPage';

    /**
     * @var string
     */
    protected const TAB_TRANSLATED_TITLE = 'global.search.pages';

    /**
     * {@inheritDoc}
     *  - Calculates total hist for cms pages tab via CmsPageSearch client module's method call
     *
     * @api
     *
     * @param string $searchString
     * @param array $requestParams
     *
     * @return int
     */
    public function calculateItemCount(string $searchString, array $requestParams = []): int
    {
        return $this
            ->getFactory()
            ->getCmsPageSearchClient()
            ->searchCount($searchString, $requestParams);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\TabMetaDataTransfer
     */
    public function getTabMetaData(): TabMetaDataTransfer
    {
        $tabsMetaDataTransfer = (new TabMetaDataTransfer())
            ->setName(static::NAME)
            ->setTitle(static::TAB_TRANSLATED_TITLE)
            ->setRoute(CmsSearchPageRouteProviderPlugin::ROUTE_NAME_SEARCH);

        return $tabsMetaDataTransfer;
    }
}
