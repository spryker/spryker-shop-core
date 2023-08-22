<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ShipmentPage\Plugin\CustomerReorderWidget;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use SprykerShop\Yves\ShipmentPage\Plugin\CustomerReorderWidget\ShipmentReorderItemSanitizerPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group ShipmentPage
 * @group CustomerReorderWidget
 * @group ShipmentReorderItemSanitizerPluginTest
 * Add your own group annotations below this line
 */
class ShipmentReorderItemSanitizerPluginTest extends Unit
{
    /**
     * @dataProvider getShipmentReorderItemSanitizerDataProvider
     *
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    public function testSuccessfullyRemovesShipmentWithArrayOfItems(array $itemTransfers): void
    {
        // Act
        $itemTransfers = (new ShipmentReorderItemSanitizerPlugin())->execute($itemTransfers);

        // Assert
        foreach ($itemTransfers as $itemTransfer) {
            $this->assertNull($itemTransfer->getShipment());
        }
    }

    /**
     * @return list<list<list<\Generated\Shared\Transfer\ItemTransfer>>>
     */
    protected function getShipmentReorderItemSanitizerDataProvider(): array
    {
        return [
            [
                [
                    (new ItemTransfer())->setShipment(
                        (new ShipmentTransfer()),
                    ),
                    (new ItemTransfer())->setShipment(
                        (new ShipmentTransfer()),
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
