<?php

namespace SprykerShop\Yves\TabsWidgetExtension\Plugin;

use Generated\Shared\Transfer\TabMetaDataTransfer;

interface FullTextSearchTabPluginInterface
{
    /**
     * Specification:
     *  - Calculates amount of items for tab. Shouldn't be executed on active tab
     *
     * @api
     *
     * @param string $searchString
     * @param array $requestParams
     *
     * @return int
     */
    public function calculateItemCount(string $searchString, array $requestParams = []): int;

    /**
     * Specification:
     *  - Returns TabMetaDataTransfer with current tab info
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\TabMetaDataTransfer
     */
    public function getTabMetaData(): TabMetaDataTransfer;
}
