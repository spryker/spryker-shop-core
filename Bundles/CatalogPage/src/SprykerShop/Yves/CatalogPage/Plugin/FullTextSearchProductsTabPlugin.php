<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Plugin;

use Generated\Shared\Transfer\TabMetaDataTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CatalogPage\Plugin\Provider\CatalogPageControllerProvider;
use SprykerShop\Yves\TabsWidgetExtension\Plugin\FullTextSearchTabPluginInterface;

class FullTextSearchProductsTabPlugin extends AbstractPlugin implements FullTextSearchTabPluginInterface
{
    protected const NAME = 'FullTextSearchProductsTab';

    protected const TITLE = 'global.search.suggestion.in_products';

    /**
     * {@inheritdoc}
     *
     * Specification
     *  - Counts product search result
     *
     * @api
     *
     * @return int
     */
    public function getTabCount(string $searchString, array $requestParams = []): int
    {
        return 1; //Will be provided in next user story
    }

    /**
     * {@inheritdoc}
     *
     * Specification:
     *  - Returns TabsMetaDataTransfer with product tab information
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\TabMetaDataTransfer
     */
    public function getTabMetaData(): TabMetaDataTransfer
    {
        $tabsMetaDataTransfer = (new TabMetaDataTransfer())
            ->setName(static::NAME)
            ->setRoute(CatalogPageControllerProvider::ROUTE_SEARCH)
            ->setTitle(static::TITLE);

        return $tabsMetaDataTransfer;
    }
}
