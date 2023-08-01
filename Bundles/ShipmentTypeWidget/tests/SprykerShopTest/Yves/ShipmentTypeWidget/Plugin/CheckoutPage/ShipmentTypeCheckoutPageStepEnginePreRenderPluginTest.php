<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ShipmentTypeWidget\Plugin\CheckoutPage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use SprykerShop\Yves\ShipmentTypeWidget\Plugin\CheckoutPage\ShipmentTypeCheckoutPageStepEnginePreRenderPlugin;
use SprykerShopTest\Yves\ShipmentTypeWidget\ShipmentTypeWidgetYvesTester;

class ShipmentTypeCheckoutPageStepEnginePreRenderPluginTest extends Unit
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
    public function testExecuteExpandsQuoteItemsWhenItemWithShipmentTypeUuidGiven(): void
    {
        // Arrange
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())
            ->setUuid(static::TEST_UUID_1);
        $itemTransfer = (new ItemTransfer())
            ->setShipment(new ShipmentTransfer())
            ->setShipmentType($shipmentTypeTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer);

        // Act
        $quoteTransfer = (new ShipmentTypeCheckoutPageStepEnginePreRenderPlugin())->execute($quoteTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $this->assertSame(
            $itemTransfer->getShipmentTypeOrFail()->getUuidOrFail(),
            $itemTransfer->getShipmentOrFail()->getShipmentTypeUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testExecuteDoesntExpandQuoteItemsWhenItemWithoutShipmentTypeGiven(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())
            ->setShipment(new ShipmentTransfer());
        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer);

        // Act
        $quoteTransfer = (new ShipmentTypeCheckoutPageStepEnginePreRenderPlugin())->execute($quoteTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $this->assertEmpty(
            $itemTransfer->getShipmentOrFail()->getShipmentTypeUuid(),
        );
    }

    /**
     * @return void
     */
    public function testExecuteDoesntOverrideItemsShipmentShipmentTypeUuidWhenItemWithoutShipmentTypeGiven(): void
    {
        // Arrange
        $shipmentTransfer = (new ShipmentTransfer())
            ->setShipmentTypeUuid(static::TEST_UUID_1);
        $itemTransfer = (new ItemTransfer())
            ->setShipment($shipmentTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer);

        // Act
        $quoteTransfer = (new ShipmentTypeCheckoutPageStepEnginePreRenderPlugin())->execute($quoteTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $this->assertSame(static::TEST_UUID_1, $itemTransfer->getShipmentOrFail()->getShipmentTypeUuid());
    }
}
