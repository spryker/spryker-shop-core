<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ShipmentTypeWidget\Plugin\CustomerPage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use SprykerShop\Yves\ShipmentTypeWidget\Plugin\CustomerPage\ShipmentTypeCheckoutAddressStepPreGroupItemsByShipmentPlugin;
use SprykerShopTest\Yves\ShipmentTypeWidget\ShipmentTypeWidgetYvesTester;

class ShipmentTypeCheckoutAddressStepPreGroupItemsByShipmentPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_UUID_1 = 'uuid1';

    /**
     * @var \SprykerShopTest\Yves\ShipmentTypeWidget\ShipmentTypeWidgetYvesTester
     */
    protected ShipmentTypeWidgetYvesTester $tester;

    /**
     * @return void
     */
    public function testPreGroupItemsByShipmentCleansShipmentTypeUuidsFromQuoteItems(): void
    {
        // Arrange
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())->setUuid(static::TEST_UUID_1);
        $shipmentMethodTransfer = (new ShipmentMethodTransfer())->setShipmentType($shipmentTypeTransfer);
        $shipmentTransfer = (new ShipmentTransfer())
            ->setMethod($shipmentMethodTransfer)
            ->setShipmentTypeUuid(static::TEST_UUID_1);
        $itemTransfer = (new ItemTransfer())
            ->setShipment($shipmentTransfer);

        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer);

        // Act
        $quoteTransfer = (new ShipmentTypeCheckoutAddressStepPreGroupItemsByShipmentPlugin())->preGroupItemsByShipment($quoteTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $this->assertEmpty(
            $itemTransfer->getShipmentOrFail()->getShipmentTypeUuid(),
        );
        $this->assertEmpty(
            $itemTransfer->getShipmentOrFail()->getMethodOrFail()->getShipmentType(),
        );
    }
}
