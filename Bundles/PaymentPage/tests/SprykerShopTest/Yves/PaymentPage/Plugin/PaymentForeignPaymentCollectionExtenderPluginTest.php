<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\PaymentPage\Plugin;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use SprykerShop\Yves\PaymentPage\PaymentPageFactory;
use SprykerShop\Yves\PaymentPage\Plugin\PaymentPage\PaymentForeignPaymentCollectionExtenderPlugin;
use SprykerShop\Yves\PaymentPage\Plugin\StepEngine\PaymentForeignSubFormPlugin;

class PaymentForeignPaymentCollectionExtenderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testExtendCollectionReturnsCorrectSubFormPluginCollectionWhenRequestIsCorrect(): void
    {
        // Arrange
        $subFormPluginCollection = new SubFormPluginCollection();
        $paymentMethodTransfer = (new PaymentMethodTransfer())
            ->setPaymentAuthorizationEndpoint('test_endpoint');
        $paymentMethodsTransfer = (new PaymentMethodsTransfer())->setMethods(
            new ArrayObject([$paymentMethodTransfer]),
        );

        // Act
        $responseSubFormPluginCollection = $this->createPaymentForeignPaymentCollectionExtenderPluginMock()
            ->extendCollection(
                $subFormPluginCollection,
                $paymentMethodsTransfer,
            );

        // Assert
        $this->assertInstanceOf(SubFormPluginCollection::class, $responseSubFormPluginCollection);
        $this->assertCount(1, $responseSubFormPluginCollection);
    }

    /**
     * @return void
     */
    public function testExtendCollectionReturnsEmptyCorrectSubFormPluginCollectionWhenRequestIsNotCorrect(): void
    {
        // Arrange
        $subFormPluginCollection = new SubFormPluginCollection();
        $paymentMethodTransfer = new PaymentMethodTransfer();
        $paymentMethodsTransfer = (new PaymentMethodsTransfer())->setMethods(
            new ArrayObject([$paymentMethodTransfer]),
        );

        // Act
        $responseSubFormPluginCollection = $this->createPaymentForeignPaymentCollectionExtenderPluginMock()
            ->extendCollection(
                $subFormPluginCollection,
                $paymentMethodsTransfer,
            );

        // Assert
        $this->assertInstanceOf(SubFormPluginCollection::class, $responseSubFormPluginCollection);
        $this->assertEquals($subFormPluginCollection, $responseSubFormPluginCollection);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\PaymentPage\Plugin\PaymentPage\PaymentForeignPaymentCollectionExtenderPlugin
     */
    protected function createPaymentForeignPaymentCollectionExtenderPluginMock(): PaymentForeignPaymentCollectionExtenderPlugin
    {
        $paymentForeignPaymentCollectionExtenderPlugin = $this->getMockBuilder(PaymentForeignPaymentCollectionExtenderPlugin::class)
            ->onlyMethods([
                'getFactory',
            ])
            ->getMock();
        $paymentForeignPaymentCollectionExtenderPlugin->method('getFactory')->willReturn($this->createPaymentPageFactoryMock());

        return $paymentForeignPaymentCollectionExtenderPlugin;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\PaymentPage\PaymentPageFactory
     */
    protected function createPaymentPageFactoryMock(): PaymentPageFactory
    {
        $paymentPageFactoryMock = $this->createMock(PaymentPageFactory::class);
        $paymentPageFactoryMock->method('createPaymentForeignSubFormPlugin')->willReturn(
            new PaymentForeignSubFormPlugin(),
        );

        return $paymentPageFactoryMock;
    }
}
