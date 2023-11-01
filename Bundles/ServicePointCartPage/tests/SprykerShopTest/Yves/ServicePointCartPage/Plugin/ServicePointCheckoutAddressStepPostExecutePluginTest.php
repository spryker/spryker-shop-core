<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ServicePointPage\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToLocaleClientInterface;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToMessengerClientInterface;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToQuoteClientInterface;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToServicePointCartClientInterface;
use SprykerShop\Yves\ServicePointCartPage\Plugin\CartPage\ServicePointCheckoutAddressStepPostExecutePlugin;
use SprykerShop\Yves\ServicePointCartPage\ServicePointCartPageDependencyProvider;
use SprykerShopTest\Yves\ServicePointCartPage\ServicePointCartPageTester;

/**
 * @group SprykerShop
 * @group Yves
 * @group ServicePointCartPage
 * @group Plugin
 * @group ServicePointCheckoutAddressStepPostExecutePluginTest
 */
class ServicePointCheckoutAddressStepPostExecutePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const ITEM_GROUP_KEY = 'key';

    /**
     * @var string
     */
    protected const QUOTE_ERROR_MESSAGE = 'message';

    /**
     * @var \SprykerShopTest\Yves\ServicePointCartPage\ServicePointCartPageTester
     */
    protected ServicePointCartPageTester $tester;

    /**
     * @return void
     */
    public function testReplacementSuccessful(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer());
        $quoteReplacementResponseTransfer = (new QuoteReplacementResponseTransfer())->setQuote($quoteTransfer);

        $this->tester->setDependency(
            ServicePointCartPageDependencyProvider::CLIENT_QUOTE,
            $this->createQuoteClientMock(),
        );

        $this->tester->setDependency(
            ServicePointCartPageDependencyProvider::CLIENT_GLOSSARY_STORAGE,
            $this->createGlossaryStorageClientMock(false),
        );

        $this->tester->setDependency(
            ServicePointCartPageDependencyProvider::CLIENT_MESSENGER,
            $this->createMessengerClientMock(false),
        );

        $this->tester->setDependency(
            ServicePointCartPageDependencyProvider::CLIENT_LOCALE,
            $this->createLocaleClientMock(false),
        );

        $this->tester->setDependency(
            ServicePointCartPageDependencyProvider::CLIENT_SERVICE_POINT_CART,
            $this->createServicePointCartClientMock($quoteReplacementResponseTransfer),
        );

        // Act
        $quoteResponseTransfer = (new ServicePointCheckoutAddressStepPostExecutePlugin())->execute($quoteTransfer);

        // Assert
        $this->assertSame($quoteTransfer, $quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return void
     */
    public function testUnsetRequiredItemPropertiesWhileReplacementFails(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())
            ->setServicePoint(new ServicePointTransfer())
            ->setShipment(new ShipmentTransfer())
            ->setShipmentType(new ShipmentTypeTransfer())
            ->setGroupKey(static::ITEM_GROUP_KEY);
        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);
        $quoteReplacementResponseTransfer = (new QuoteReplacementResponseTransfer())
            ->setQuote($quoteTransfer)
            ->addError((new QuoteErrorTransfer())->setMessage(static::QUOTE_ERROR_MESSAGE))
            ->addFailedReplacementItem($itemTransfer);

        $this->tester->setDependency(
            ServicePointCartPageDependencyProvider::CLIENT_QUOTE,
            $this->createQuoteClientMock(),
        );

        $this->tester->setDependency(
            ServicePointCartPageDependencyProvider::CLIENT_GLOSSARY_STORAGE,
            $this->createGlossaryStorageClientMock(true),
        );

        $this->tester->setDependency(
            ServicePointCartPageDependencyProvider::CLIENT_MESSENGER,
            $this->createMessengerClientMock(true),
        );

        $this->tester->setDependency(
            ServicePointCartPageDependencyProvider::CLIENT_LOCALE,
            $this->createLocaleClientMock(true),
        );

        $this->tester->setDependency(
            ServicePointCartPageDependencyProvider::CLIENT_SERVICE_POINT_CART,
            $this->createServicePointCartClientMock($quoteReplacementResponseTransfer),
        );

        // Act
        $quoteResponseTransfer = (new ServicePointCheckoutAddressStepPostExecutePlugin())->execute($quoteTransfer);

        // Assert
        $this->assertSame($quoteTransfer, $quoteResponseTransfer->getQuoteTransfer());
        $this->assertCount(1, $quoteTransfer->getItems());
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $this->assertNull($itemTransfer->getServicePoint());
    }

    /**
     * @param bool $shouldExecute
     *
     * @return \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToGlossaryStorageClientInterface
     */
    protected function createGlossaryStorageClientMock(bool $shouldExecute): ServicePointCartPageToGlossaryStorageClientInterface
    {
        $invokedCount = $shouldExecute ? $this->once() : $this->never();
        $glossaryStorageClientMock = $this
            ->getMockBuilder(ServicePointCartPageToGlossaryStorageClientInterface::class)
            ->getMock();
        $glossaryStorageClientMock->expects($invokedCount)->method('translate');

        return $glossaryStorageClientMock;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer
     *
     * @return \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToServicePointCartClientInterface
     */
    protected function createServicePointCartClientMock(
        QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer
    ): ServicePointCartPageToServicePointCartClientInterface {
        $servicePointCartClientMock = $this
            ->getMockBuilder(ServicePointCartPageToServicePointCartClientInterface::class)
            ->getMock();
        $servicePointCartClientMock
            ->expects($this->once())
            ->method('replaceQuoteItems')
            ->willReturn($quoteReplacementResponseTransfer);

        return $servicePointCartClientMock;
    }

    /**
     * @param bool $shouldExecute
     *
     * @return \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToLocaleClientInterface
     */
    protected function createLocaleClientMock(bool $shouldExecute): ServicePointCartPageToLocaleClientInterface
    {
        $invokedCount = $shouldExecute ? $this->once() : $this->never();
        $localeClientMock = $this->getMockBuilder(ServicePointCartPageToLocaleClientInterface::class)
            ->getMock();
        $localeClientMock->expects($invokedCount)->method('getCurrentLocale');

        return $localeClientMock;
    }

    /**
     * @param bool $shouldExecute
     *
     * @return \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToMessengerClientInterface
     */
    protected function createMessengerClientMock(bool $shouldExecute): ServicePointCartPageToMessengerClientInterface
    {
        $invokedCount = $shouldExecute ? $this->once() : $this->never();
        $messengerClientMock = $this->getMockBuilder(ServicePointCartPageToMessengerClientInterface::class)
            ->getMock();
        $messengerClientMock->expects($invokedCount)->method('addErrorMessage');

        return $messengerClientMock;
    }

    /**
     * @return \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToQuoteClientInterface
     */
    protected function createQuoteClientMock(): ServicePointCartPageToQuoteClientInterface
    {
        $quoteClientMock = $this->getMockBuilder(ServicePointCartPageToQuoteClientInterface::class)
            ->getMock();
        $quoteClientMock->expects($this->once())->method('setQuote');

        return $quoteClientMock;
    }
}
