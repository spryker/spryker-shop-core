<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\ItemFetcher;

interface BundleItemFetcherInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array<mixed> $requestParams
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function fetchSelectedBundleItems(array $itemTransfers, array $requestParams): array;
}
