<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Dependency\Plugin\TabsWidget;

interface FullTextSearchTabsWidgetPluginInterface
{
    public const NAME = 'FullTextSearchTabsWidgetPlugin';

    /**
     * @param string $searchString
     * @param string $activeTabName
     * @param array $requestParams
     *
     * @return void
     */
    public function initialize(string $searchString, string $activeTabName, array $requestParams = []): void;
}
