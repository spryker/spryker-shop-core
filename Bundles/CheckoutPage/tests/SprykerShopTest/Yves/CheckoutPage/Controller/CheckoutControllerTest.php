<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CheckoutPage\Controller;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Yves\StepEngine\Process\StepEngine;
use Spryker\Yves\StepEngine\Process\StepEngineInterface;
use SprykerShop\Yves\CheckoutPage\CheckoutPageFactory;
use SprykerShop\Yves\CheckoutPage\Controller\CheckoutController;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface;
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
    /**
     * @var string
     */
    protected const PLACE_ORDER_URL = '/checkout/place-order';

    /**
     * @var string
     */
    protected const PLACE_ORDER_ROUTE = 'checkout-place-order';

    /**
     * @var string
     */
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
        $this->assertSame(static::SUCCESS_URL, $response->getTargetUrl());
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
    protected function createCheckoutPageFactoryMock(): CheckoutPageFactory
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
        $checkoutPageToQuoteClientBridgeMock->method('getQuote')
            ->willReturn($this->tester->createQuoteTransferWithMultiShipment());

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
}
