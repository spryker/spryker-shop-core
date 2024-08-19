<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CheckoutPage\Process\Steps;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CheckoutPage\Process\Steps\ErrorStep;
use SprykerShopTest\Yves\CheckoutPage\CheckoutPageTester;

/**
 * @group SprykerShop
 * @group Yves
 * @group CheckoutPage
 * @group Process
 * @group Steps
 * @group ErrorStepTest
 */
class ErrorStepTest extends Unit
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
     * @var \SprykerShopTest\Yves\CheckoutPage\CheckoutPageTester
     */
    protected CheckoutPageTester $tester;

    /**
     * @dataProvider postConditionDataProvider
     *
     * @param \ArrayObject $errors
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testPostConditionShouldReturnExpectedResult(ArrayObject $errors, bool $expectedResult): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->seed([
                QuoteTransfer::ERRORS => $errors,
            ])
            ->build();
        $errorStep = new ErrorStep(
            static::STEP_ROUTE,
            static::ESCAPE_ROUTE,
        );

        // Act
        $result = $errorStep->postCondition($quoteTransfer);

        // Assert
        $this->tester->assertSame($result, $expectedResult);
    }

    /**
     * @return array<string, array<\ArrayObject|bool>>
     */
    protected function postConditionDataProvider(): array
    {
        return [
            'Should return false with errors in checkout.' => [
                new ArrayObject(['error']),
                false,
            ],
            'Should return true without errors in checkout.' => [
                new ArrayObject(),
                true,
            ],
        ];
    }

    /**
     * @dataProvider preConditionDataProvider
     *
     * @param \ArrayObject $errors
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testPreConditionShouldReturnExpectedResult(ArrayObject $errors, bool $expectedResult): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->seed([
                QuoteTransfer::ERRORS => $errors,
            ])
            ->build();
        $errorStep = new ErrorStep(
            static::STEP_ROUTE,
            static::ESCAPE_ROUTE,
        );

        // Act
        $result = $errorStep->preCondition($quoteTransfer);

        // Assert
        $this->tester->assertSame($result, $expectedResult);
    }

    /**
     * @return array<string, array<\ArrayObject|bool>>
     */
    protected function preConditionDataProvider(): array
    {
        return [
            'Should return true with errors in checkout.' => [
                new ArrayObject(['error']),
                true,
            ],
            'Should return false without errors in checkout.' => [
                new ArrayObject(),
                false,
            ],
        ];
    }
}
