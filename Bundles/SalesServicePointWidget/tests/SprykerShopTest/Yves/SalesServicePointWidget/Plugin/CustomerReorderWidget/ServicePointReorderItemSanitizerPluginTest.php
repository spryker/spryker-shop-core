<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SalesServicePointWidget\Plugin\CustomerReorderWidget;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointTransfer;
use SprykerShop\Yves\SalesServicePointWidget\Plugin\CustomerReorderWidget\ServicePointReorderItemSanitizerPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group SalesServicePointWidget
 * @group ServicePointReorderItemSanitizerPluginTest
 * Add your own group annotations below this line
 */
class ServicePointReorderItemSanitizerPluginTest extends Unit
{
    /**
     * @dataProvider getServicePointReorderItemSanitizerDataProvider
     *
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    public function testSuccessfullyRemovesServicePointWithArrayOfItems(array $itemTransfers): void
    {
        // Act
        $itemTransfers = (new ServicePointReorderItemSanitizerPlugin())->execute($itemTransfers);

        // Assert
        foreach ($itemTransfers as $itemTransfer) {
            $this->assertNull($itemTransfer->getSalesOrderItemServicePoint());
        }
    }

    /**
     * @return list<list<list<\Generated\Shared\Transfer\ItemTransfer>>>
     */
    protected function getServicePointReorderItemSanitizerDataProvider(): array
    {
        return [
            [
                [
                    (new ItemTransfer())->setSalesOrderItemServicePoint(
                        (new SalesOrderItemServicePointTransfer()),
                    ),
                    (new ItemTransfer())->setSalesOrderItemServicePoint(
                        (new SalesOrderItemServicePointTransfer()),
                    ),
                ],
            ],
            [
                [
                    new ItemTransfer(),
                    new ItemTransfer(),
                ],
            ],
            [
                [],
            ],
        ];
    }
}
