<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CheckoutPage\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CheckoutPage\Plugin\StepEngine\PaymentForeignHandlerPlugin;
use Symfony\Component\HttpFoundation\Request;

class PaymentForeignHandlerPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testAddToDataClassReturnsCorrectQuoteTransferWhenRequestIsCorrect(): void
    {
        // Arrange
        $request = Request::create('test_request');
        $paymentTransfer = (new PaymentTransfer())
            ->setPaymentSelection('foreignPayments[paymentKey]')
            ->setForeignPayments([
                'paymentKey' => [
                    'paymentMethodName' => 'method',
                    'paymentProviderName' => 'provider',
                ],
            ]);
        $requestQuoteTransfer = (new QuoteTransfer())
            ->setPayment($paymentTransfer);

        // Act
        $responseQuoteTransfer = (new PaymentForeignHandlerPlugin())->addToDataClass($request, $requestQuoteTransfer);

        // Assert
        $this->assertInstanceOf(QuoteTransfer::class, $responseQuoteTransfer);
        $this->assertEquals('method', $responseQuoteTransfer->getPayment()->getPaymentMethod());
        $this->assertEquals('provider', $responseQuoteTransfer->getPayment()->getPaymentProvider());
    }
}
