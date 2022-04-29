<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Plugin;

use Generated\Shared\Transfer\TabMetaDataTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CatalogPage\Plugin\Router\CatalogPageRouteProviderPlugin;
use SprykerShop\Yves\TabsWidgetExtension\Plugin\FullTextSearchTabPluginInterface;

/**
 * @method \SprykerShop\Yves\CatalogPage\CatalogPageFactory getFactory()
 */
class FullTextSearchProductsTabPlugin extends AbstractPlugin implements FullTextSearchTabPluginInterface
{
    /**
     * @var string
     */
    protected const NAME = 'FullTextSearchProductsTab';

    /**
     * @var string
     */
    protected const TAB_TRANSLATED_TITLE = 'global.search.suggestion.in_products';

    /**
     * {@inheritDoc}
     *  - Calculates total hits for catalog page tab via Catalog module's method call
     *
     * @api
     *
     * @param string $searchString
     * @param array<string, mixed> $requestParams
     *
     * @return int
     */
    public function calculateItemCount(string $searchString, array $requestParams = []): int
    {
        return $this
            ->getFactory()
            ->getCatalogClient()
            ->catalogSearchCount($searchString, $requestParams);
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
            ->setRoute(CatalogPageRouteProviderPlugin::ROUTE_NAME_SEARCH)
            ->setTitle(static::TAB_TRANSLATED_TITLE);

        return $tabsMetaDataTransfer;
    }
}
