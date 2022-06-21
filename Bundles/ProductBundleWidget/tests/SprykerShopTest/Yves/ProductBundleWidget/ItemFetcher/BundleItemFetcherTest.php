<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ProductBundleWidget\ItemFetcher;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use SprykerShop\Yves\ProductBundleWidget\ItemFetcher\BundleItemFetcher;
use SprykerShop\Yves\ProductBundleWidget\ItemFetcher\BundleItemFetcherInterface;

class BundleItemFetcherTest extends Unit
{
    /**
     * @uses \SprykerShop\Yves\ProductBundleWidget\ItemFetcher\BundleItemFetcher::PARAM_BUNDLE_ITEM_IDENTIFIERS
     *
     * @var string
     */
    protected const PARAM_BUNDLE_ITEM_IDENTIFIERS = 'bundle-item-identifiers';

    /**
     * @var string
     */
    protected const TEST_BUNDLE_ITEM_IDENTIFIER = 'bundle-item';

    /**
     * @return void
     */
    public function testFetchSelectedBundleItemsWillReturnItemTransferWhenBundleItemIdentifierIsProvided(): void
    {
        // Arrange
        $sourceItemTransfers = [
            (new ItemBuilder())->build(),
            (new ItemBuilder([ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::TEST_BUNDLE_ITEM_IDENTIFIER]))->build(),
            (new ItemBuilder())->build(),
        ];
        $requestParams = [
            static::PARAM_BUNDLE_ITEM_IDENTIFIERS => [static::TEST_BUNDLE_ITEM_IDENTIFIER],
        ];

        // Act
        $itemTransfers = $this->createBundleItemFetcher()->fetchSelectedBundleItems($sourceItemTransfers, $requestParams);

        // Assert
        $this->assertCount(1, $itemTransfers);
        $this->assertSame(static::TEST_BUNDLE_ITEM_IDENTIFIER, $itemTransfers[0]->getBundleItemIdentifier());
    }

    /**
     * @return void
     */
    public function testFetchSelectedBundleItemsWillReturnEmptyArrayWhenBundleItemIdentifierIsNotProvided(): void
    {
        // Arrange
        $sourceItemTransfers = [
            (new ItemBuilder())->build(),
            (new ItemBuilder([ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::TEST_BUNDLE_ITEM_IDENTIFIER]))->build(),
            (new ItemBuilder())->build(),
        ];
        $requestParams = [];

        // Act
        $itemTransfers = $this->createBundleItemFetcher()->fetchSelectedBundleItems($sourceItemTransfers, $requestParams);

        // Assert
        $this->assertCount(0, $itemTransfers);
    }

    /**
     * @return void
     */
    public function testFetchSelectedBundleItemsWillReturnEmptyArrayWhenProvidedBundleItemIdentifierIsNotFoundInItemTransfers(): void
    {
        // Arrange
        $sourceItemTransfers = [
            (new ItemBuilder())->build(),
            (new ItemBuilder([ItemTransfer::BUNDLE_ITEM_IDENTIFIER => static::TEST_BUNDLE_ITEM_IDENTIFIER]))->build(),
            (new ItemBuilder())->build(),
        ];
        $requestParams = [
            static::PARAM_BUNDLE_ITEM_IDENTIFIERS => ['non-existing-bundle-identifier'],
        ];

        // Act
        $itemTransfers = $this->createBundleItemFetcher()->fetchSelectedBundleItems($sourceItemTransfers, $requestParams);

        // Assert
        $this->assertCount(0, $itemTransfers);
    }

    /**
     * @return \SprykerShop\Yves\ProductBundleWidget\ItemFetcher\BundleItemFetcherInterface
     */
    protected function createBundleItemFetcher(): BundleItemFetcherInterface
    {
        return new BundleItemFetcher();
    }
}
