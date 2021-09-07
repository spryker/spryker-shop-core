<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CheckoutPage;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Price\PriceConfig;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class CheckoutPageTester extends Actor
{
    use _generated\CheckoutPageTesterActions;

    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     * @var string
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithMultiShipment(): QuoteTransfer
    {
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::PRICE_MODE => PriceConfig::PRICE_MODE_NET,
            QuoteTransfer::ORDER_REFERENCE => 'order-reference',
        ]))
            ->withItem(
                (new ItemBuilder())
                    ->withShipment(
                        (new ShipmentBuilder([ShipmentTransfer::SHIPMENT_SELECTION => 'custom']))
                            ->withShippingAddress()
                            ->withMethod()
                    )
            )
            ->withAnotherItem(
                (new ItemBuilder())
                    ->withShipment(
                        (new ShipmentBuilder([ShipmentTransfer::SHIPMENT_SELECTION => 'custom']))
                            ->withShippingAddress()
                            ->withMethod()
                    )
            )
            ->withBillingAddress()
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
            ->withPayment()
            ->build();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $quoteTransfer->addExpense(
                (new ExpenseTransfer())->setType(static::SHIPMENT_EXPENSE_TYPE)
                    ->setShipment($itemTransfer->getShipment())
            );
        }

        return $quoteTransfer;
    }
}
