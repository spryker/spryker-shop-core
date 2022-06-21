<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\ItemFetcher;

use Generated\Shared\Transfer\ItemTransfer;

class BundleItemFetcher implements BundleItemFetcherInterface
{
    /**
     * @var string
     */
    protected const PARAM_BUNDLE_ITEM_IDENTIFIERS = 'bundle-item-identifiers';

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array<mixed> $requestParams
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function fetchSelectedBundleItems(array $itemTransfers, array $requestParams): array
    {
        /** @var array<int, string>|null $bundleItemIdentifiers */
        $bundleItemIdentifiers = $requestParams[static::PARAM_BUNDLE_ITEM_IDENTIFIERS] ?? null;
        if (!$bundleItemIdentifiers) {
            return [];
        }

        $fetchedBundleItemTransfers = [];
        foreach ($bundleItemIdentifiers as $bundleItemIdentifier) {
            $bundleItemTransfer = $this->extractBundleItemTransferByBundleItemIdentifier($itemTransfers, $bundleItemIdentifier);
            if (!$bundleItemTransfer) {
                continue;
            }

            $fetchedBundleItemTransfers[] = $bundleItemTransfer;
        }

        return $fetchedBundleItemTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param string $bundleItemIdentifier
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function extractBundleItemTransferByBundleItemIdentifier(array $itemTransfers, string $bundleItemIdentifier): ?ItemTransfer
    {
        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getBundleItemIdentifier() && $itemTransfer->getBundleItemIdentifierOrFail() === $bundleItemIdentifier) {
                return $itemTransfer;
            }
        }

        return null;
    }
}
