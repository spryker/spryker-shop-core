<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSearchPage\Plugin;

use Generated\Shared\Transfer\TabMetaDataTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CmsSearchPage\Plugin\Provider\CmsSearchPageControllerProvider;
use SprykerShop\Yves\TabsWidgetExtension\Plugin\FullTextSearchTabPluginInterface;

class FullTextSearchCmsPageTabPlugin extends AbstractPlugin implements FullTextSearchTabPluginInterface
{
    protected const NAME = 'FullTextSearchCmsPage';

    protected const TAB_TRANSLATED_TITLE = 'global.search.pages';

    /**
     * {@inheritdoc}
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
        return 1; //Will be provided in next user story
    }

    /**
     * {@inheritdoc}
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
            ->setRoute(CmsSearchPageControllerProvider::ROUTE_SEARCH);

        return $tabsMetaDataTransfer;
    }
}
