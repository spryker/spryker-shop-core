<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Handler;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPriceClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface;
use SprykerShop\Yves\CheckoutPage\Exception\NotAvailableShipmentMethodException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use \SprykerShop\Yves\CheckoutPage\Handler\ShipmentStepExecutor instead.
 */
class ShipmentHandler implements ShipmentHandlerInterface
{
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

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
    public function addShipmentToQuote(Request $request, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $shipmentTransfer = $quoteTransfer->getShipment();

        $shipmentMethodTransfer = $this->getShipmentMethod($quoteTransfer);
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        $shipmentExpenseTransfer = $this->createShippingExpenseTransfer($shipmentMethodTransfer, $quoteTransfer->getPriceMode());
        $shipmentExpenseTransfer->setShipment($shipmentTransfer);
        $this->replaceShipmentExpenseInQuote($quoteTransfer, $shipmentExpenseTransfer);

        $quoteTransfer = $this->clearItemsShipment($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function getShipmentMethod(QuoteTransfer $quoteTransfer): ?ShipmentMethodTransfer
    {
        $selectedShipmentMethod = $quoteTransfer->getShipment()->getShipmentSelection();

        if ($selectedShipmentMethod === CheckoutPageConfig::SHIPMENT_METHOD_NAME_NO_SHIPMENT) {
            return $this->getShipmentMethodNoShipment($quoteTransfer);
        }

        return $this->getShipmentMethodById($quoteTransfer, (int)$selectedShipmentMethod);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \SprykerShop\Yves\CheckoutPage\Exception\NotAvailableShipmentMethodException
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function getShipmentMethodNoShipment(QuoteTransfer $quoteTransfer): ShipmentMethodTransfer
    {
        $availableShipmentMethodsTransfer = $this->getAvailableShipmentMethods($quoteTransfer);
        foreach ($availableShipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getName() === CheckoutPageConfig::SHIPMENT_METHOD_NAME_NO_SHIPMENT) {
                return $shipmentMethodTransfer;
            }
        }

        throw new NotAvailableShipmentMethodException(sprintf(
            'Shipment method "%s" is not available. Please make sure to create this shipment method before using it.',
            CheckoutPageConfig::SHIPMENT_METHOD_NAME_NO_SHIPMENT
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function getShipmentMethodById(QuoteTransfer $quoteTransfer, int $idShipmentMethod): ?ShipmentMethodTransfer
    {
        $shipmentMethodsTransfer = $this->getAvailableShipmentMethods($quoteTransfer);

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
        $shipmentMethodsTransfer = current($this->shipmentClient->getAvailableMethodsByShipment($quoteTransfer)->getShipmentMethods());
        if (!$shipmentMethodsTransfer) {
            return new ShipmentMethodsTransfer();
        }

        return $shipmentMethodsTransfer;
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
        $shipmentExpenseTransfer->setType(static::SHIPMENT_EXPENSE_TYPE);
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
            if ($expense->getType() !== static::SHIPMENT_EXPENSE_TYPE) {
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

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function clearItemsShipment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment(null);
        }

        return $quoteTransfer;
    }
}
