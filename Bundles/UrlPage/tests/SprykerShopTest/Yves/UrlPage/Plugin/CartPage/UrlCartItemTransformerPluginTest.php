<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\UrlPage\Plugin\CustomerPage;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use SprykerShop\Yves\UrlPage\Dependency\Client\UrlPageToUrlStorageClientInterface;
use SprykerShop\Yves\UrlPage\Plugin\CartPage\UrlCartItemTransformerPlugin;
use SprykerShop\Yves\UrlPage\UrlPageDependencyProvider;
use SprykerShopTest\Yves\UrlPage\UrlPageYvesTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group UrlPage
 * @group Plugin
 * @group CustomerPage
 * @group UrlCartItemTransformerPluginTest
 * Add your own group annotations below this line
 */
class UrlCartItemTransformerPluginTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\UrlPage\UrlPageYvesTester
     */
    protected UrlPageYvesTester $tester;

    /**
     * @return void
     */
    public function testShouldSanitizeUrlWhenItemHasNotExistingUrl(): void
    {
        // Arrange
        $urlCartItemTransformerPlugin = new UrlCartItemTransformerPlugin();
        $itemTransfers = [
            (new ItemTransfer())->setUrl('/not-existing-url'),
            (new ItemTransfer())->setUrl('/not-existing-url-2'),
        ];
        $quoteTransfer = (new QuoteTransfer())->setItems(new ArrayObject($itemTransfers));
        $this->mockUrlStorageClient([], 1);

        // Act
        $itemTransfers = $urlCartItemTransformerPlugin->transformCartItems($itemTransfers, $quoteTransfer);

        // Assert
        foreach ($itemTransfers as $itemTransfer) {
            $this->assertNull($itemTransfer->getUrl());
        }
    }

    /**
     * @return void
     */
    public function testShouldWorkWhenItemHasNoUrl(): void
    {
        // Arrange
        $urlCartItemTransformerPlugin = new UrlCartItemTransformerPlugin();
        $itemTransfers = [
            new ItemTransfer(),
        ];
        $quoteTransfer = (new QuoteTransfer())->setItems(new ArrayObject($itemTransfers));
        $this->mockUrlStorageClient([], 0);

        // Act
        $itemTransfers = $urlCartItemTransformerPlugin->transformCartItems($itemTransfers, $quoteTransfer);

        // Assert
        foreach ($itemTransfers as $itemTransfer) {
            $this->assertNull($itemTransfer->getUrl());
        }
    }

    /**
     * @return void
     */
    public function testShouldKeepUrlWhenItemHasExistingUrl(): void
    {
        // Arrange
        $urlCartItemTransformerPlugin = new UrlCartItemTransformerPlugin();
        $itemTransfer = (new ItemTransfer())->setUrl('/existing-url');
        $itemTransfers = [
            $itemTransfer,
        ];
        $quoteTransfer = (new QuoteTransfer())->setItems(new ArrayObject($itemTransfers));

        $urlStorageTransfers = [
            $itemTransfer->getUrlOrFail() => (new UrlStorageTransfer())->setUrl($itemTransfer->getUrlOrFail()),
        ];
        $this->mockUrlStorageClient($urlStorageTransfers, 1);

        // Act
        $itemTransfers = $urlCartItemTransformerPlugin->transformCartItems($itemTransfers, $quoteTransfer);

        // Assert
        foreach ($itemTransfers as $itemTransfer) {
            $this->assertNotNull($itemTransfer->getUrl());
        }
    }

    /**
     * @param list<string> $response
     * @param int $methodCallCount
     *
     * @return void
     */
    protected function mockUrlStorageClient(array $response, int $methodCallCount): void
    {
        $urlStorageClientMock = $this->createMock(UrlPageToUrlStorageClientInterface::class);
        $urlStorageClientMock->expects($this->exactly($methodCallCount))
            ->method('getUrlStorageTransferByUrls')
            ->willReturn($response);

        $this->tester->setDependency(UrlPageDependencyProvider::CLIENT_URL_STORAGE, $urlStorageClientMock);
    }
}
