<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin;

/**
 * Implement this plugin if you want to fetch items by provided request parameters during partial reordering.
 */
interface ReorderItemFetcherPluginInterface
{
    /**
     * Specification:
     * - Fetches `ItemTransfers` by provided request parameters.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array<mixed> $requestParams
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function execute(array $itemTransfers, array $requestParams): array;
}
