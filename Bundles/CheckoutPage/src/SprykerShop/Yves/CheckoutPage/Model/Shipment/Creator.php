<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Model\Shipment;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPriceClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use SprykerShop\Yves\CheckoutPage\Handler\ShipmentHandler;
use Symfony\Component\HttpFoundation\Request;

class Creator extends ShipmentHandler
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface $shipmentClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPriceClientInterface $priceClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface $shipmentService
     */
    public function __construct(
        CheckoutPageToShipmentClientInterface $shipmentClient,
        CheckoutPageToPriceClientInterface $priceClient,
        CheckoutPageToShipmentServiceInterface $shipmentService
    ) {
        parent::__construct($shipmentClient, $priceClient);

        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addShipmentToQuote(Request $request, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer = $this->groupQuoteShipmentGroupTransferItemsByHash($quoteTransfer);

        $availableShipmentMethodsGroupedByShipment = $this->getAvailableMethodsByShipment($quoteTransfer)->getGroups();
        $quoteTransfer = $this->setShipmentMethodsToQuoteShipmentGroups($quoteTransfer, $availableShipmentMethodsGroupedByShipment);

        $quoteShipmentGroups = $quoteTransfer->getShipmentGroups();
        $quoteShipmentGroups = $this->setShipmentGroupsSelectedMethodTransfer($quoteShipmentGroups);
        $quoteTransfer = $this->setShipmentExpenseTransfers($quoteTransfer);

        if ($quoteShipmentGroups->count() === 1) {
            $this->addQuoteLevelShipment(
                $quoteTransfer,
                $quoteShipmentGroups->offsetGet($quoteShipmentGroups->getIterator()->key())->getShipment()
            );
        }

        return $quoteTransfer;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addQuoteLevelShipment(QuoteTransfer $quoteTransfer, ShipmentTransfer $shipmentTransfer): QuoteTransfer
    {
        $quoteTransfer->setShipment($shipmentTransfer);
        $quoteTransfer->setShippingAddress($shipmentTransfer->getShippingAddress());

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function groupQuoteShipmentGroupTransferItemsByHash(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $shipmentGroupsCollection = $this->groupItemsByShipment($quoteTransfer->getItems());
        $quoteShipmentGroupCollection = $quoteTransfer->getShipmentGroups();

        foreach ($shipmentGroupsCollection as $shipmentGroupTransfer) {
            foreach ($quoteShipmentGroupCollection as $quoteShipmentGroupTransfer) {
                if ($shipmentGroupTransfer->getHash() !== $quoteShipmentGroupTransfer->getHash()) {
                    continue;
                }

                $shipmentGroupTransfer->setShipment($quoteShipmentGroupTransfer->getShipment());
            }
        }

        $quoteTransfer->setShipmentGroups($shipmentGroupsCollection);

        return $quoteTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function groupItemsByShipment(ArrayObject $itemTransfers): ArrayObject
    {
        return $this->shipmentService->groupItemsByShipment($itemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    protected function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentGroupCollectionTransfer
    {
        return $this->shipmentClient->getAvailableMethodsByShipment($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $availableShippingMethodsGroupedByShipment
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setShipmentMethodsToQuoteShipmentGroups(
        QuoteTransfer $quoteTransfer,
        ArrayObject $availableShippingMethodsGroupedByShipment
    ): QuoteTransfer {
        foreach ($quoteTransfer->getShipmentGroups() as $shipmentGroupTransfer) {
            foreach ($availableShippingMethodsGroupedByShipment as $availableShipmentMethodsShipmentGroupTransfer) {
                if ($shipmentGroupTransfer->getHash() !== $availableShipmentMethodsShipmentGroupTransfer->getHash()) {
                    continue;
                }

                $shipmentGroupTransfer->setAvailableShipmentMethods($availableShipmentMethodsShipmentGroupTransfer->getAvailableShipmentMethods());
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function setShipmentGroupsSelectedMethodTransfer(ArrayObject $shipmentGroupCollection): ArrayObject
    {
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentMethodTransfer = $this->findShipmentMethodById(
                $shipmentGroupTransfer->getAvailableShipmentMethods(),
                $shipmentGroupTransfer->getShipment()->getShipmentSelection()
            );
            $shipmentGroupTransfer->getShipment()->setMethod($shipmentMethodTransfer);
        }

        return $shipmentGroupCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function findShipmentMethodById(ShipmentMethodsTransfer $shipmentMethodsTransfer, int $idShipmentMethod): ?ShipmentMethodTransfer
    {
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodsTransfer) {
            if ($shipmentMethodsTransfer->getIdShipmentMethod() === $idShipmentMethod) {
                return $shipmentMethodsTransfer;
            }
        }

        return null;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setShipmentExpenseTransfers(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $priceMode = $quoteTransfer->getPriceMode();
        $quoteTransfer = $this->removeAllShipmentExpensesFromQuote($quoteTransfer);

        foreach ($quoteTransfer->getShipmentGroups() as $shipmentGroupTransfer) {
            $shipmentGroupTransfer->requireShipment();
            $shipmentGroupTransfer->getShipment()->requireMethod();

            $shippingExpenseTransfer = $this->createQuoteShippingExpenseTransfer(
                $shipmentGroupTransfer->getShipment(),
                $priceMode
            );
            $quoteTransfer->addExpense($shippingExpenseTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeAllShipmentExpensesFromQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $notShipmentExpensesCollection = new ArrayObject();

        foreach ($quoteTransfer->getExpenses() as $expense) {
            if ($expense->getType() === ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $notShipmentExpensesCollection->append($expense);
        }

        $quoteTransfer->setExpenses($notShipmentExpensesCollection);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createQuoteShippingExpenseTransfer(ShipmentTransfer $shipmentTransfer, $priceMode): ExpenseTransfer
    {
        $shipmentMethodTransfer = $shipmentTransfer->getMethod();

        $shipmentExpenseTransfer = new ExpenseTransfer();
        $shipmentExpenseTransfer->fromArray($shipmentMethodTransfer->toArray(), true);
        $shipmentExpenseTransfer->setType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE);
        $this->setPrice($shipmentExpenseTransfer, $shipmentMethodTransfer->getStoreCurrencyPrice(), $priceMode);
        $shipmentExpenseTransfer->setQuantity(1);
        $shipmentExpenseTransfer->setShipment($shipmentTransfer);

        return $shipmentExpenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param int $price
     * @param string $priceMode
     *
     * @return void
     */
    protected function setPrice(ExpenseTransfer $shipmentExpenseTransfer, $price, $priceMode): void
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
     * @todo Check usages.
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function setItemShipmentTransfers(ArrayObject $shipmentGroupCollection): ArrayObject
    {
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
                $itemTransfer->setShipment($shipmentGroupTransfer->getShipment());
            }
        }

        return $shipmentGroupCollection;
    }
}
