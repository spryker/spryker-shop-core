<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CheckoutPage\Process\Steps;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PlaceOrderStep;
use SprykerShopTest\Yves\CheckoutPage\CheckoutPageTester;

/**
 * @group SprykerShop
 * @group Yves
 * @group CheckoutPage
 * @group Process
 * @group Steps
 * @group PlaceOrderStepTest
 */
class PlaceOrderStepTest extends Unit
{
    /**
     * @var string
     */
    protected const STEP_ROUTE = 'stepRoute';

    /**
     * @var string
     */
    protected const ESCAPE_ROUTE = 'escapeRoute';

    /**
     * @var string
     */
    protected const LOCALE_EN_US = 'en_US';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'error message';

    /**
     * @var \SprykerShopTest\Yves\CheckoutPage\CheckoutPageTester
     */
    protected CheckoutPageTester $tester;

    /**
     * @return void
     */
    public function testPostConditionReturnsFalseIfCheckoutNotConfirmed(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->seed([
                QuoteTransfer::CHECKOUT_CONFIRMED => false,
            ])
            ->build();
        $checkoutClientMock = $this->createMock(CheckoutPageToCheckoutClientInterface::class);
        $flashMessengerMock = $this->createMock(FlashMessengerInterface::class);
        $glossaryStorageClientMock = $this->createMock(CheckoutPageToGlossaryStorageClientInterface::class);
        $placeOrderStep = new PlaceOrderStep(
            $checkoutClientMock,
            $flashMessengerMock,
            static::LOCALE_EN_US,
            $glossaryStorageClientMock,
            static::STEP_ROUTE,
            static::ESCAPE_ROUTE,
        );

        // Act
        $result = $placeOrderStep->postCondition($quoteTransfer);

        // Assert
        $this->tester->assertFalse($result);
    }

    /**
     * @return void
     */
    public function tesPostConditionReturnsTrueIfCheckoutError(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->seed([
                QuoteTransfer::CHECKOUT_CONFIRMED => true,
                QuoteTransfer::ERRORS => [
                    (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE),
                ],
            ])
            ->build();
        $checkoutClientMock = $this->createMock(CheckoutPageToCheckoutClientInterface::class);
        $flashMessengerMock = $this->createMock(FlashMessengerInterface::class);
        $glossaryStorageClientMock = $this->createMock(CheckoutPageToGlossaryStorageClientInterface::class);
        $placeOrderStep = new PlaceOrderStep(
            $checkoutClientMock,
            $flashMessengerMock,
            static::LOCALE_EN_US,
            $glossaryStorageClientMock,
            static::STEP_ROUTE,
            static::ESCAPE_ROUTE,
        );

        // Act
        $result = $placeOrderStep->postCondition($quoteTransfer);

        // Assert
        $this->tester->assertTrue($result);
    }
}
