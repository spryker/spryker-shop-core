<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CheckoutPage\Controller;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Checkout\CheckoutConfig;
use Spryker\Shared\Price\PriceConfig;
use Spryker\Yves\StepEngine\Process\StepEngine;
use Spryker\Yves\StepEngine\Process\StepEngineInterface;
use SprykerShop\Yves\CheckoutPage\CheckoutPageFactory;
use SprykerShop\Yves\CheckoutPage\Controller\CheckoutController;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface;
use SprykerShop\Yves\CustomerPage\Form\AddressForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group SprykerShop
 * @group Yves
 * @group CheckoutPage
 * @group Controller
 * @group CheckoutControllerTest
 */
class CheckoutControllerTest extends Unit
{
    protected const PLACE_ORDER_URL = '/checkout/place-order';
    protected const PLACE_ORDER_ROUTE = 'checkout-place-order';
    protected const SUCCESS_URL = '/checkout/success';

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Controller\CheckoutController
     */
    protected $controller;

    /**
     * @var \SprykerShopTest\Yves\CheckoutPage\CheckoutPageTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->createCheckoutControllerMock();
    }

    /**
     * @return void
     */
    public function testPlaceOrderActionExecutesCheckoutPlaceOrderStep(): void
    {
        // Arrange
        $request = Request::create(static::PLACE_ORDER_URL, Request::METHOD_POST);
        $request->request->set('_route', static::PLACE_ORDER_ROUTE);

        // Act
        $response = $this->controller->placeOrderAction($request);

        // Assert
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame($response->getTargetUrl(), static::SUCCESS_URL);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\CheckoutPage\Controller\CheckoutController
     */
    protected function createCheckoutControllerMock(): CheckoutController
    {
        $checkoutControllerMock = $this->getMockBuilder(CheckoutController::class)
            ->onlyMethods([
                'getFactory',
                'redirectResponseInternal',
                'canProceedCheckout',
                'createStepProcess',
            ])
            ->getMock();

        $checkoutControllerMock->method('getFactory')->willReturn($this->createCheckoutPageFactoryMock());
        $checkoutControllerMock->method('redirectResponseInternal')->willReturn(new RedirectResponse(static::SUCCESS_URL));
        $checkoutControllerMock->method('canProceedCheckout')->willReturn((new QuoteValidationResponseTransfer())->setIsSuccessful(true));
        $checkoutControllerMock->method('createStepProcess')->willReturn($this->createStepEngineMock());

        return $checkoutControllerMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\CheckoutPage\CheckoutPageFactory
     */
    protected function createCheckoutPageFactoryMock()
    {
        $checkoutPageFactoryMock = $this->createMock(CheckoutPageFactory::class);
        $checkoutPageFactoryMock->method('getQuoteClient')->willReturn($this->createCheckoutPageToQuoteClientBridgeMock());

        return $checkoutPageFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface
     */
    protected function createCheckoutPageToQuoteClientBridgeMock(): CheckoutPageToQuoteClientInterface
    {
        $checkoutPageToQuoteClientBridgeMock = $this->createMock(CheckoutPageToQuoteClientInterface::class);
        $checkoutPageToQuoteClientBridgeMock->method('getQuote')->willReturn($this->createQuoteTransfer());

        return $checkoutPageToQuoteClientBridgeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\StepEngine\Process\StepEngineInterface
     */
    protected function createStepEngineMock(): StepEngineInterface
    {
        $placeOrderStepMock = $this->createMock(StepEngine::class);
        $placeOrderStepMock->expects($this->once())->method('process')->willReturn(new RedirectResponse(static::SUCCESS_URL));

        return $placeOrderStepMock;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setPriceMode(PriceConfig::PRICE_MODE_GROSS);

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIsGuest(false);
        $quoteTransfer->setCustomer($customerTransfer);

        $addressTransfer = new AddressTransfer();
        $address = [
            AddressForm::FIELD_SALUTATION => 'Mr',
            AddressForm::FIELD_FIRST_NAME => 'Hans',
            AddressForm::FIELD_LAST_NAME => 'Muster',
            AddressForm::FIELD_ADDRESS_1 => 'Any Street',
            AddressForm::FIELD_ADDRESS_2 => '23',
            AddressForm::FIELD_CITY => 'Berlin',
            AddressForm::FIELD_ZIP_CODE => '12347',
            AddressForm::FIELD_ISO_2_CODE => 'DE',
        ];
        $addressTransfer->fromArray($address);
        $quoteTransfer->setBillingAddress($addressTransfer);

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setShippingAddress($addressTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setShipment($shipmentTransfer);
        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setType(CheckoutConfig::SHIPMENT_EXPENSE_TYPE);
        $expenseTransfer->setUnitGrossPrice(1000);
        $expenseTransfer->setQuantity(1);
        $quoteTransfer->addExpense($expenseTransfer);

        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setGrandTotal(50000);
        $quoteTransfer->setTotals($totalsTransfer);

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentProvider('paymentProvider');
        $paymentTransfer->setPaymentMethod('paymentMethod');
        $quoteTransfer->setPayment($paymentTransfer);
        $quoteTransfer->setOrderReference('order-reference');

        return $quoteTransfer;
    }
}
