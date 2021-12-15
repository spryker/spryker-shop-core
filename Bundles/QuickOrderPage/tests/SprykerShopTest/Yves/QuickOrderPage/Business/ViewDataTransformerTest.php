<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\QuickOrderPage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use SprykerShop\Yves\QuickOrderPage\ViewDataTransformer\ViewDataTransformer;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormColumnPluginInterface;

/**
 * @group SprykerShop
 * @group Yves
 * @group QuickOrderPage
 * @group Business
 * @group ViewDataTransformerTest
 */
class ViewDataTransformerTest extends Unit
{
    /**
     * @var string
     */
    protected const PATH = 'baseMeasurementUnit.name';

    /**
     * @var string
     */
    protected const PRODUCT_MEASUREMENT_UNIT_NAME = 'Name';

    /**
     * @var \QuickOrderPageTest\Yves\QuickOrderPage\QuickOrderPageTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testTransformProductData(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConcreteTransfer->setBaseMeasurementUnit((new ProductMeasurementUnitTransfer())->setName(static::PRODUCT_MEASUREMENT_UNIT_NAME));
        // Act
        $getDataByPath = (new ViewDataTransformer())->transformProductData([$productConcreteTransfer], [$this->getQuickOrderFormColumnPluginMock()]);

        // Assert
        $this->assertIsArray($getDataByPath);
        $this->assertIsArray($getDataByPath[$productConcreteTransfer->getSku()]);
        $this->assertIsArray($getDataByPath[$productConcreteTransfer->getSku()]['columns']);
        $this->assertEquals($productConcreteTransfer->getSku(), $getDataByPath[$productConcreteTransfer->getSku()]['sku']);
        $this->assertEquals($productConcreteTransfer->getName(), $getDataByPath[$productConcreteTransfer->getSku()]['name']);
        $this->assertEquals(static::PATH, array_key_first($getDataByPath[$productConcreteTransfer->getSku()]['columns']));
        $this->assertEquals(static::PRODUCT_MEASUREMENT_UNIT_NAME, $getDataByPath[$productConcreteTransfer->getSku()]['columns'][static::PATH]);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormColumnPluginInterface
     */
    protected function getQuickOrderFormColumnPluginMock(): QuickOrderFormColumnPluginInterface
    {
        $quickOrderFormColumnPluginMock = $this->getMockBuilder(QuickOrderFormColumnPluginInterface::class)->getMock();
        $quickOrderFormColumnPluginMock->method('getDataPath')->willReturn(static::PATH);

        return $quickOrderFormColumnPluginMock;
    }
}
