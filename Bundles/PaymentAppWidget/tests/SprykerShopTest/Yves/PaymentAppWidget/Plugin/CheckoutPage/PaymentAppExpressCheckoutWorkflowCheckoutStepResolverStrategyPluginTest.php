<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\PaymentAppWidget\Plugin\CheckoutPage;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CheckoutConfigurationTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig;
use SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetDependencyProvider;
use SprykerShop\Yves\PaymentAppWidget\Plugin\CheckoutPage\PaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPlugin;
use SprykerShopTest\Yves\PaymentAppWidget\PaymentAppWidgetTester;
use SprykerShopTest\Yves\PaymentAppWidget\TestStepInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group PaymentAppWidget
 * @group Plugin
 * @group CheckoutPage
 * @group PaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPluginTest
 * Add your own group annotations below this line
 */
class PaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPluginTest extends Unit
{
    protected PaymentAppWidgetTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setRequestStackService($this->createRequestStack());
    }

    /**
     * @return void
     */
    public function testPaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPluginRemovesStepsDefinedInConfig(): void
    {
        // Arrange
        $steps = [
            $this->createConfiguredMock(TestStepInterface::class, [
                'getCode' => 'step1',
            ]),
            $this->createConfiguredMock(TestStepInterface::class, [
                'getCode' => 'step3',
            ]),
        ];

        $configMock = $this->tester->mockConfigMethod(
            'getCheckoutStepsToSkipInExpressCheckoutWorkflow',
            ['step1', 'step2'],
        );
        $factoryMock = $this->tester->getFactory();
        $factoryMock->setConfig($configMock);

        $plugin = new PaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPlugin();
        $plugin->setFactory($factoryMock);

        // Act
        $stepsFiltered = $plugin->execute($steps, new QuoteTransfer());

        // Assert
        $this->assertCount(1, $stepsFiltered);
        $this->assertSame($stepsFiltered[0]->getCode(), 'step3');
    }

    /**
     * @return void
     */
    public function testPaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPluginIsApplicableIfCorrectCheckoutStrategyWasSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::PAYMENTS => [
                [
                    PaymentTransfer::CHECKOUT_CONFIGURATION => [
                        CheckoutConfigurationTransfer::STRATEGY => PaymentAppWidgetConfig::CHECKOUT_CONFIGURATION_STRATEGY_EXPRESS_CHECKOUT,
                    ],
                ],
            ],
        ]))->build();

        // Act
        $result = (new PaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPlugin())->isApplicable($quoteTransfer);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testPaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPluginIsNotApplicableIfCorrectCheckoutStrategyWasNotSet(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();

        // Act
        $result = (new PaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPlugin())->isApplicable($quoteTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testPaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPluginDoNotRemovesStepsWhenRouteMatchesStartPageRouteName(): void
    {
        // Arrange
        $steps = [
            $this->createConfiguredMock(TestStepInterface::class, [
                'getCode' => 'step1',
            ]),
            $this->createConfiguredMock(TestStepInterface::class, [
                'getCode' => 'step2',
            ]),
        ];

        $quoteTransfer = new QuoteTransfer();

        $configMock = $this->tester->mockConfigMethod('getExpressCheckoutStartPageRouteName', 'test_route');
        $factoryMock = $this->tester->getFactory();
        $factoryMock->setConfig($configMock);

        $plugin = new PaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPlugin();
        $plugin->setFactory($factoryMock);

        // Act
        $stepsFiltered = $plugin->execute($steps, $quoteTransfer);

        // Assert
        $this->assertSame($steps, $stepsFiltered);
    }

    /**
     * @return void
     */
    public function testPaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPluginCleanQuoteFieldsWhenRouteMatchesStartPageRouteName(): void
    {
        // Arrange
        $steps = [
            $this->createConfiguredMock(TestStepInterface::class, [
                'getCode' => 'step1',
            ]),
            $this->createConfiguredMock(TestStepInterface::class, [
                'getCode' => 'step2',
            ]),
        ];

        $quoteTransfer = (new QuoteTransfer())->setPayments(new ArrayObject([
            (new PaymentTransfer())->setPaymentProvider('test_provider'),
        ]));

        $this->tester->mockConfigMethod('getQuoteFieldsToCleanInExpressCheckoutWorkflow', [QuoteTransfer::PAYMENTS]);
        $configMock = $this->tester->mockConfigMethod('getExpressCheckoutStartPageRouteName', 'test_route');
        $factoryMock = $this->tester->getFactory();
        $factoryMock->setConfig($configMock);

        $plugin = new PaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPlugin();
        $plugin->setFactory($factoryMock);

        // Act
        $stepsFiltered = $plugin->execute($steps, $quoteTransfer);

        // Assert
        $this->assertSame($steps, $stepsFiltered);
        $this->assertCount(0, $quoteTransfer->getPayments());
    }

    /**
     * @return void
     */
    public function testPaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPluginDoesNotCleanQuoteFieldsWhenFieldListIsEmptyAndRouteMatchesStartPageRouteName(): void
    {
        // Arrange
        $steps = [
            $this->createConfiguredMock(TestStepInterface::class, [
                'getCode' => 'step1',
            ]),
            $this->createConfiguredMock(TestStepInterface::class, [
                'getCode' => 'step2',
            ]),
        ];

        $quoteTransfer = (new QuoteTransfer())->setPayments(new ArrayObject([
            (new PaymentTransfer())->setPaymentProvider('test_provider'),
        ]));

        $configMock = $this->tester->mockConfigMethod('getExpressCheckoutStartPageRouteName', 'test_route');
        $factoryMock = $this->tester->getFactory();
        $factoryMock->setConfig($configMock);

        $plugin = new PaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPlugin();
        $plugin->setFactory($factoryMock);

        // Act
        $stepsFiltered = $plugin->execute($steps, $quoteTransfer);

        // Assert
        $this->assertSame($steps, $stepsFiltered);
        $this->assertSame($quoteTransfer->getPayments(), $quoteTransfer->getPayments());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    protected function createRequestStack(): RequestStack
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request([], [], ['_route' => 'test_route']));

        return $requestStack;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     *
     * @return void
     */
    protected function setRequestStackService(RequestStack $requestStack): void
    {
        $this->tester->setDependency(PaymentAppWidgetDependencyProvider::SERVICE_REQUEST_STACK, $requestStack);
    }
}
