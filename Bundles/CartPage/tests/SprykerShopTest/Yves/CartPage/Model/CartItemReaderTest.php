<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CartPage\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CartPage\Model\CartItemReader;
use SprykerShop\Yves\CartPageExtension\Dependency\Plugin\CartItemTransformerPluginInterface;
use SprykerShopTest\Yves\CartPage\CartPageTester;

/**
 * @group SprykerShop
 * @group Yves
 * @group CartPage
 * @group Model
 * @group CartItemReaderTest
 */
class CartItemReaderTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\CartPage\CartPageTester
     */
    protected CartPageTester $tester;

    /**
     * @return void
     */
    public function testExecutesCartItemTransformerPlugin(): void
    {
        // Arrange
        $cartItemReader = new CartItemReader([
            $this->createCartItemTransformerPluginMock(),
        ]);

        // Act
        $cartItemReader->getCartItems(new QuoteTransfer());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\CartPageExtension\Dependency\Plugin\CartItemTransformerPluginInterface
     */
    protected function createCartItemTransformerPluginMock(): CartItemTransformerPluginInterface
    {
        $cartItemTransformerPluginMock = $this->getMockBuilder(CartItemTransformerPluginInterface::class)
            ->getMock();

        $cartItemTransformerPluginMock
            ->expects($this->once())
            ->method('transformCartItems')
            ->willReturn([]);

        return $cartItemTransformerPluginMock;
    }
}
