<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Handler;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPriceClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface;
use Symfony\Component\HttpFoundation\Request;

class ShipmentHandler implements ShipmentHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface
     */
    protected $shipmentClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface $shipmentClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPriceClientInterface $priceClient
     */
    public function __construct(
        CheckoutPageToShipmentClientInterface $shipmentClient,
        CheckoutPageToPriceClientInterface $priceClient
    ) {
        $this->shipmentClient = $shipmentClient;
        $this->priceClient = $priceClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addShipmentToQuote(Request $request, QuoteTransfer $quoteTransfer)
    {
        $shipmentTransfer = $quoteTransfer->getShipment();

        $shipmentMethodTransfer = $this->getShipmentMethodById($quoteTransfer);
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        $shipmentExpenseTransfer = $this->createShippingExpenseTransfer($shipmentMethodTransfer, $quoteTransfer->getPriceMode());
        $this->replaceShipmentExpenseInQuote($quoteTransfer, $shipmentExpenseTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function getShipmentMethodById(QuoteTransfer $quoteTransfer)
    {
        $shipmentMethodsTransfer = $this->getAvailableShipmentMethods($quoteTransfer);
        $idShipmentMethod = $quoteTransfer->getShipment()->getShipmentSelection();

        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodsTransfer) {
            if ($shipmentMethodsTransfer->getIdShipmentMethod() === $idShipmentMethod) {
                return $shipmentMethodsTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getAvailableShipmentMethods(QuoteTransfer $quoteTransfer)
    {
        return $this->shipmentClient->getAvailableMethods($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShippingExpenseTransfer(ShipmentMethodTransfer $shipmentMethodTransfer, $priceMode)
    {
        $shipmentExpenseTransfer = $this->createExpenseTransfer();
        $shipmentExpenseTransfer->fromArray($shipmentMethodTransfer->toArray(), true);
        $shipmentExpenseTransfer->setType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE);
        $this->setPrice($shipmentExpenseTransfer, $shipmentMethodTransfer->getStoreCurrencyPrice(), $priceMode);
        $shipmentExpenseTransfer->setQuantity(1);

        return $shipmentExpenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param int $price
     * @param string $priceMode
     *
     * @return void
     */
    protected function setPrice(ExpenseTransfer $shipmentExpenseTransfer, $price, $priceMode)
    {
        if ($priceMode === $this->priceClient->getNetPriceModeIdentifier()) {
            $shipmentExpenseTransfer->setUnitGrossPrice(0);
            $shipmentExpenseTransfer->setSumGrossPrice(0);
            $shipmentExpenseTransfer->setUnitNetPrice($price);
            return;
        }

        $shipmentExpenseTransfer->setUnitNetPrice(0);
        $shipmentExpenseTransfer->setSumNetPrice(0);
        $shipmentExpenseTransfer->setUnitGrossPrice($price);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return void
     */
    protected function replaceShipmentExpenseInQuote(QuoteTransfer $quoteTransfer, ExpenseTransfer $expenseTransfer)
    {
        $otherExpenseCollection = new ArrayObject();
        foreach ($quoteTransfer->getExpenses() as $expense) {
            if ($expense->getType() !== ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                $otherExpenseCollection->append($expense);
            }
        }

        $quoteTransfer->setExpenses($otherExpenseCollection);
        $quoteTransfer->addExpense($expenseTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }
}
