<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerShopTest\Yves\PaymentAppWidget\Plugin\CheckoutPage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToSalesClientInterface;
use SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetFactory;
use SprykerShop\Yves\PaymentAppWidget\Plugin\CheckoutPage\PaymentAppCancelOrderOnSummaryPageAfterRedirectFromHostedPaymentPagePlugin;
use SprykerShopTest\Yves\PaymentAppWidget\PaymentAppWidgetTester;

/**
 * @group SprykerShopTest
 * @group Yves
 * @group PaymentAppWidget
 * @group Plugin
 * @group CheckoutPage
 * @group PaymentAppCancelOrderOnSummaryPageAfterRedirectFromHostedPaymentPagePluginTest
 */
class PaymentAppCancelOrderOnSummaryPageAfterRedirectFromHostedPaymentPagePluginTest extends Unit
{
    protected PaymentAppWidgetTester $tester;

    /**
     * @var string
     */
    protected const ORDER_REFERENCE = 'TEST-ORDER-REF';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'Error occurred';

    /**
     * @return void
     */
    public function testPreConditionSkipsWhenANonExpressCheckoutPaymentMethodIsUsed(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setOrderReference(static::ORDER_REFERENCE);

        // Act
        $resultQuoteTransfer = $this->createPlugin()->preCondition($quoteTransfer);

        // Assert
        $this->assertSame($quoteTransfer, $resultQuoteTransfer);
    }

    /**
     * @return void
     */
    public function testPreConditionSkipsWhenNoOrderReference(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setOrderReference(null);

        // Act
        $resultQuoteTransfer = $this->createPlugin()->preCondition($quoteTransfer);

        // Assert
        $this->assertSame($quoteTransfer, $resultQuoteTransfer);
    }

    /**
     * @return void
     */
    public function testPreConditionCancelsOrderSuccessfullyWhenExpressCheckoutPaymentMethodIsUsed(): void
    {
        // Arrange
        $paymentTransfer = (new PaymentTransfer())
            ->setPaymentSelection('foreignPayments[catface]');

        $quoteTransfer = (new QuoteTransfer())
            ->setPayment($paymentTransfer)
            ->setOrderReference(static::ORDER_REFERENCE);

        $plugin = $this->createPluginWithMockedSuccessfulResponse();

        // Act
        $resultQuoteTransfer = $plugin->preCondition($quoteTransfer);

        // Assert
        $this->assertNull($resultQuoteTransfer->getOrderReference());
        $this->assertEmpty($resultQuoteTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testPreConditionHandlesFailedCancellation(): void
    {
        // Arrange
        $paymentTransfer = (new PaymentTransfer())
            ->setPaymentSelection('foreignPayments[catface]');

        $quoteTransfer = (new QuoteTransfer())
            ->setPayment($paymentTransfer)
            ->setOrderReference(static::ORDER_REFERENCE);

        $plugin = $this->createPluginWithMockedFailedResponse();

        // Act
        $resultQuoteTransfer = $plugin->preCondition($quoteTransfer);

        // Assert
        $this->assertSame(static::ORDER_REFERENCE, $resultQuoteTransfer->getOrderReference());
        $this->assertCount(1, $resultQuoteTransfer->getErrors());
        $this->assertSame(static::ERROR_MESSAGE, $resultQuoteTransfer->getErrors()[0]->getMessage());
    }

    /**
     * @return \SprykerShop\Yves\PaymentAppWidget\Plugin\CheckoutPage\PaymentAppCancelOrderOnSummaryPageAfterRedirectFromHostedPaymentPagePlugin
     */
    protected function createPlugin(): PaymentAppCancelOrderOnSummaryPageAfterRedirectFromHostedPaymentPagePlugin
    {
        return new PaymentAppCancelOrderOnSummaryPageAfterRedirectFromHostedPaymentPagePlugin();
    }

    /**
     * @return \SprykerShop\Yves\PaymentAppWidget\Plugin\CheckoutPage\PaymentAppCancelOrderOnSummaryPageAfterRedirectFromHostedPaymentPagePlugin
     */
    protected function createPluginWithMockedSuccessfulResponse(): PaymentAppCancelOrderOnSummaryPageAfterRedirectFromHostedPaymentPagePlugin
    {
        $salesClientMock = $this->createMock(PaymentAppWidgetToSalesClientInterface::class);
        $salesClientMock->method('cancelOrder')
            ->with($this->callback(function (OrderCancelRequestTransfer $orderCancelRequestTransfer) {
                return $orderCancelRequestTransfer->getOrderReference() === static::ORDER_REFERENCE
                    && $orderCancelRequestTransfer->getAllowCancellationWithoutCustomer() === true;
            }))
            ->willReturn((new OrderCancelResponseTransfer())->setIsSuccessful(true));

        $factoryMock = $this->getMockBuilder(PaymentAppWidgetFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getSalesClient'])
            ->getMock();
        $factoryMock->method('getSalesClient')->willReturn($salesClientMock);

        $plugin = $this->createPlugin();
        $plugin->setFactory($factoryMock);

        return $plugin;
    }

    /**
     * @return \SprykerShop\Yves\PaymentAppWidget\Plugin\CheckoutPage\PaymentAppCancelOrderOnSummaryPageAfterRedirectFromHostedPaymentPagePlugin
     */
    protected function createPluginWithMockedFailedResponse(): PaymentAppCancelOrderOnSummaryPageAfterRedirectFromHostedPaymentPagePlugin
    {
        $salesClientMock = $this->createMock(PaymentAppWidgetToSalesClientInterface::class);
        $salesClientMock->method('cancelOrder')
            ->willReturn(
                (new OrderCancelResponseTransfer())
                    ->setIsSuccessful(false)
                    ->addMessage((new MessageTransfer())->setMessage(static::ERROR_MESSAGE)),
            );

        $factoryMock = $this->getMockBuilder(PaymentAppWidgetFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getSalesClient'])
            ->getMock();
        $factoryMock->method('getSalesClient')->willReturn($salesClientMock);

        $plugin = $this->createPlugin();
        $plugin->setFactory($factoryMock);

        return $plugin;
    }
}
