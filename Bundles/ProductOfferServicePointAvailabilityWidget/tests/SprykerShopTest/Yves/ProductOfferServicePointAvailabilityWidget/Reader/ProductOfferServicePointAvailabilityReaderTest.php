<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ProductOfferServicePointAvailabilityWidget\Reader;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ServicePointSearchTransfer;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder\ServicePointAvailabilityMessageBuilder;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder\ServicePointAvailabilityMessageBuilderInterface;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToCartClientInterface;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorClientInterface;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\ProductOfferServicePointAvailabilityReader;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\ProductOfferServicePointAvailabilityReaderInterface;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\QuoteItemReader;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\QuoteItemReaderInterface;
use SprykerShopTest\Yves\ProductOfferServicePointAvailabilityWidget\ProductOfferServicePointAvailabilityWidgetYvesTester;

class ProductOfferServicePointAvailabilityReaderTest extends Unit
{
    /**
     * @uses \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder\ServicePointAvailabilityMessageBuilder::GLOSSARY_KEY_ALL_ITEMS_AVAILABLE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_ALL_ITEMS_AVAILABLE = 'product_offer_service_point_availability_widget.all_items_available';

    /**
     * @uses \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder\ServicePointAvailabilityMessageBuilder::GLOSSARY_KEY_SOME_ITEMS_NOT_AVAILABLE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_SOME_ITEMS_NOT_AVAILABLE = 'product_offer_service_point_availability_widget.some_items_not_available';

    /**
     * @uses \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder\ServicePointAvailabilityMessageBuilder::GLOSSARY_KEY_SOME_ITEMS_NOT_AVAILABLE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_ALL_ITEMS_NOT_AVAILABLE = 'product_offer_service_point_availability_widget.all_items_not_available';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_OFFER_REFERENCE_1 = 'product-offer-reference-1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_OFFER_REFERENCE_2 = 'product-offer-reference-2';

    /**
     * @var \SprykerShopTest\Yves\ProductOfferServicePointAvailabilityWidget\ProductOfferServicePointAvailabilityWidgetYvesTester
     */
    protected ProductOfferServicePointAvailabilityWidgetYvesTester $tester;

    /**
     * @dataProvider availabilityMessagesDataProvider
     *
     * @param bool $isFirstProductAvailable
     * @param bool $isSecondProductAvailable
     * @param string $expectedAvailabilityMessage
     *
     * @return void
     */
    public function testAvailabilityMessages(
        bool $isFirstProductAvailable,
        bool $isSecondProductAvailable,
        string $expectedAvailabilityMessage
    ): void {
        // Arrange
        $servicePointSearchTransfer = (new ServicePointSearchTransfer())->setUuid(uniqid());

        $productOfferServicePointAvailabilityResponseItemTransfers = [
            $servicePointSearchTransfer->getUuidOrFail() => [
                $this->tester->createProductOfferServicePointAvailabilityResponseItemTransfer($isFirstProductAvailable),
                $this->tester->createProductOfferServicePointAvailabilityResponseItemTransfer($isSecondProductAvailable),
            ],
        ];

        $quoteTransfer = $this->tester->createQuoteTransfer([
            QuoteTransfer::ITEMS => [
                (new ItemTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1)->toArray(),
                (new ItemTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_2)->toArray(),
            ]]);

        $productOfferServicePointAvailabilityReader = $this->createProductOfferServicePointAvailabilityReader(
            $quoteTransfer,
            $productOfferServicePointAvailabilityResponseItemTransfers,
        );

        // Act
        $productOfferServicePointAvailabilities = $productOfferServicePointAvailabilityReader->getProductOfferServicePointAvailabilities(
            [$servicePointSearchTransfer],
            [],
        );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertSame($expectedAvailabilityMessage, $productOfferServicePointAvailabilities[$servicePointSearchTransfer->getUuidOrFail()]);
    }

    /**
     * @return void
     */
    public function testReturnsDifferentAvailabilitiesPerSeveralServicePoints(): void
    {
        // Arrange
        $firstServicePointSearchTransfer = (new ServicePointSearchTransfer())->setUuid(uniqid());
        $secondServicePointSearchTransfer = (new ServicePointSearchTransfer())->setUuid(uniqid());
        $thirdServicePointSearchTransfer = (new ServicePointSearchTransfer())->setUuid(uniqid());

        $productOfferServicePointAvailabilityResponseItemTransfers = [
            $firstServicePointSearchTransfer->getUuidOrFail() => [
                $this->tester->createProductOfferServicePointAvailabilityResponseItemTransfer(true),
                $this->tester->createProductOfferServicePointAvailabilityResponseItemTransfer(true),
            ],
            $secondServicePointSearchTransfer->getUuidOrFail() => [
                $this->tester->createProductOfferServicePointAvailabilityResponseItemTransfer(false),
                $this->tester->createProductOfferServicePointAvailabilityResponseItemTransfer(false),
            ],
            $thirdServicePointSearchTransfer->getUuidOrFail() => [
                $this->tester->createProductOfferServicePointAvailabilityResponseItemTransfer(true),
                $this->tester->createProductOfferServicePointAvailabilityResponseItemTransfer(false),
            ],
        ];

        $quoteTransfer = $this->tester->createQuoteTransfer([
            QuoteTransfer::ITEMS => [
                (new ItemTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1)->toArray(),
                (new ItemTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_2)->toArray(),
            ]]);

        $productOfferServicePointAvailabilityReader = $this->createProductOfferServicePointAvailabilityReader(
            $quoteTransfer,
            $productOfferServicePointAvailabilityResponseItemTransfers,
        );

        // Act
        $productOfferServicePointAvailabilities = $productOfferServicePointAvailabilityReader->getProductOfferServicePointAvailabilities(
            [$firstServicePointSearchTransfer, $secondServicePointSearchTransfer, $thirdServicePointSearchTransfer],
            [],
        );

        // Assert
        $this->assertCount(3, $productOfferServicePointAvailabilities);
        $this->assertSame(static::GLOSSARY_KEY_ALL_ITEMS_AVAILABLE, $productOfferServicePointAvailabilities[$firstServicePointSearchTransfer->getUuidOrFail()]);
        $this->assertSame(static::GLOSSARY_KEY_ALL_ITEMS_NOT_AVAILABLE, $productOfferServicePointAvailabilities[$secondServicePointSearchTransfer->getUuidOrFail()]);
        $this->assertSame(static::GLOSSARY_KEY_SOME_ITEMS_NOT_AVAILABLE, $productOfferServicePointAvailabilities[$thirdServicePointSearchTransfer->getUuidOrFail()]);
    }

    /**
     * @return void
     */
    public function testFiltersCartItemsWhenGroupKeysAreProvided(): void
    {
        // Arrange, Assert
        $productOfferServicePointAvailabilityReaderMock = $this->createProductOfferServicePointAvailabilityReaderWithMockedQuoteItemReader(true);

        // Act
        $productOfferServicePointAvailabilityReaderMock->getProductOfferServicePointAvailabilities(
            [(new ServicePointSearchTransfer())->setUuid(uniqid())],
            ['groupKey'],
        );
    }

    /**
     * @return void
     */
    public function testUsesAllCartItemsWhenGroupKeysAreProvided(): void
    {
        // Arrange, Assert
        $productOfferServicePointAvailabilityReaderMock = $this->createProductOfferServicePointAvailabilityReaderWithMockedQuoteItemReader(false);

        // Act
        $productOfferServicePointAvailabilityReaderMock->getProductOfferServicePointAvailabilities(
            [(new ServicePointSearchTransfer())->setUuid(uniqid())],
            [],
        );
    }

    /**
     * @return void
     */
    public function testRecognizesItemsWithoutProductOfferReferenceAsNotAvailable(): void
    {
        // Arrange
        $servicePointSearchTransfer = (new ServicePointSearchTransfer())->setUuid(uniqid());

        $productOfferServicePointAvailabilityResponseItemTransfers = [
            $servicePointSearchTransfer->getUuidOrFail() => [
                $this->tester->createProductOfferServicePointAvailabilityResponseItemTransfer(true),
            ],
        ];

        $quoteTransfer = $this->tester->createQuoteTransfer([
            QuoteTransfer::ITEMS => [
                (new ItemTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1)->toArray(),
                (new ItemTransfer())->toArray(),
            ]]);

        $productOfferServicePointAvailabilityReader = $this->createProductOfferServicePointAvailabilityReader(
            $quoteTransfer,
            $productOfferServicePointAvailabilityResponseItemTransfers,
        );

        // Act
        $productOfferServicePointAvailabilities = $productOfferServicePointAvailabilityReader->getProductOfferServicePointAvailabilities(
            [$servicePointSearchTransfer],
            [],
        );

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertSame(static::GLOSSARY_KEY_SOME_ITEMS_NOT_AVAILABLE, $productOfferServicePointAvailabilities[$servicePointSearchTransfer->getUuidOrFail()]);
    }

    /**
     * @return array
     */
    protected function availabilityMessagesDataProvider(): array
    {
        return [
            [true, true, static::GLOSSARY_KEY_ALL_ITEMS_AVAILABLE],
            [true, false, static::GLOSSARY_KEY_SOME_ITEMS_NOT_AVAILABLE],
            [false, false, static::GLOSSARY_KEY_ALL_ITEMS_NOT_AVAILABLE],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilityResponseItemTransfers
     *
     * @return \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\ProductOfferServicePointAvailabilityReaderInterface
     */
    protected function createProductOfferServicePointAvailabilityReader(
        QuoteTransfer $quoteTransfer,
        array $productOfferServicePointAvailabilityResponseItemTransfers
    ): ProductOfferServicePointAvailabilityReaderInterface {
        return new ProductOfferServicePointAvailabilityReader(
            $this->createQuoteItemReader($quoteTransfer),
            $this->createServicePointAvailabilityMessageBuilder(),
            $this->createProductOfferServicePointAvailabilityCalculatorClientMock($productOfferServicePointAvailabilityResponseItemTransfers),
        );
    }

    /**
     * @param bool $extractItemsFromQuoteByGroupKeysMethodShouldBeCalled
     *
     * @return \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\ProductOfferServicePointAvailabilityReaderInterface
     */
    protected function createProductOfferServicePointAvailabilityReaderWithMockedQuoteItemReader(
        bool $extractItemsFromQuoteByGroupKeysMethodShouldBeCalled
    ): ProductOfferServicePointAvailabilityReaderInterface {
        return new ProductOfferServicePointAvailabilityReader(
            $this->createQuoteItemReaderMock($extractItemsFromQuoteByGroupKeysMethodShouldBeCalled),
            $this->createServicePointAvailabilityMessageBuilder(),
            $this->createProductOfferServicePointAvailabilityCalculatorClientMock([]),
        );
    }

    /**
     * @param bool $extractItemsFromQuoteByGroupKeysMethodShouldBeCalled
     *
     * @return \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\QuoteItemReaderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteItemReaderMock(bool $extractItemsFromQuoteByGroupKeysMethodShouldBeCalled): QuoteItemReaderInterface
    {
        $productOfferServicePointAvailabilityReaderMock = $this->getMockBuilder(QuoteItemReader::class)
            ->setConstructorArgs([
                $this->createCartClientMock(new QuoteTransfer()),
            ])
            ->onlyMethods(['extractItemsFromQuoteByGroupKeys'])
            ->getMock();

        $productOfferServicePointAvailabilityReaderMock
            ->expects($extractItemsFromQuoteByGroupKeysMethodShouldBeCalled ? $this->once() : $this->never())
            ->method('extractItemsFromQuoteByGroupKeys');

        return $productOfferServicePointAvailabilityReaderMock;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\QuoteItemReaderInterface
     */
    protected function createQuoteItemReader(QuoteTransfer $quoteTransfer): QuoteItemReaderInterface
    {
        return new QuoteItemReader(
            $this->createCartClientMock($quoteTransfer),
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder\ServicePointAvailabilityMessageBuilderInterface
     */
    protected function createServicePointAvailabilityMessageBuilder(): ServicePointAvailabilityMessageBuilderInterface
    {
        return new ServicePointAvailabilityMessageBuilder();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToCartClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createCartClientMock(QuoteTransfer $quoteTransfer): ProductOfferServicePointAvailabilityWidgetToCartClientInterface
    {
        $cartClientMock = $this->getMockBuilder(
            ProductOfferServicePointAvailabilityWidgetToCartClientInterface::class,
        )->getMock();

        $cartClientMock
            ->method('getQuote')
            ->willReturn($quoteTransfer);

        return $cartClientMock;
    }

    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilityResponseItemTransfers
     *
     * @return \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductOfferServicePointAvailabilityCalculatorClientMock(
        array $productOfferServicePointAvailabilityResponseItemTransfers
    ): ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorClientInterface {
        $ProductOfferServicePointAvailabilityCalculatorClientMock = $this->getMockBuilder(
            ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorClientInterface::class,
        )->getMock();

        $ProductOfferServicePointAvailabilityCalculatorClientMock
            ->method('calculateProductOfferServicePointAvailabilities')
            ->willReturn($productOfferServicePointAvailabilityResponseItemTransfers);

        return $ProductOfferServicePointAvailabilityCalculatorClientMock;
    }
}
