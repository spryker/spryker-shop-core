<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Handler;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Shared\Shipment\ShipmentConstants;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPriceClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
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
        $this->shipmentClient = $shipmentClient;
        $this->priceClient = $priceClient;
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addShipmentToItems(Request $request, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $shipmentGroupsWithItems = $this->groupItemsByShipment($quoteTransfer->getItems());
        $quoteTransfer->setShipmentGroups($this->addAvailableShipmentMethods($quoteTransfer)->getGroups());

        $this->updateItemsLink($quoteTransfer->getShipmentGroups(), $shipmentGroupsWithItems);
        $this->addMethodTransfer($quoteTransfer->getShipmentGroups());
        $this->addExpenseTransfer($quoteTransfer->getShipmentGroups(), $quoteTransfer->getPriceMode());

        foreach ($quoteTransfer->getShipmentGroups() as $shipmentGroupTransfer){
            foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
                $itemTransfer->setShipment(
                  $shipmentGroupTransfer->getShipment()
                );
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollectionTo
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollectionFrom
     *
     * @return \ArrayObject
     */
    protected function updateItemsLink(ArrayObject $shipmentGroupCollectionTo, ArrayObject $shipmentGroupCollectionFrom): void
    {
        $shipmentGroupIndexedByHash = $this->doIndexByHash($shipmentGroupCollectionFrom);
        foreach ($shipmentGroupCollectionTo as $shipmentGroupTransfer) {
            $shipmentGroupTransfer->setItems(
                $shipmentGroupIndexedByHash[$shipmentGroupTransfer->getHash()]->getItems()
            );
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return void
     */
    protected function addMethodTransfer(ArrayObject $shipmentGroupCollection): void
    {
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentMethodTransfer = $this->findShipmentMethodById(
                $shipmentGroupTransfer->getAvailableShipmentMethods(),
                $shipmentGroupTransfer->getShipment()->getShipmentSelection()
            );
            $shipmentGroupTransfer->getShipment()->setMethod($shipmentMethodTransfer);
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     * @param string $priceMode
     *
     * @return void
     */
    protected function addExpenseTransfer(ArrayObject $shipmentGroupCollection, $priceMode): void
    {
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shippingExpenseTransfer = $this->createShippingExpenseTransfer(
                $shipmentGroupTransfer->getShipment()->getMethod(),
                $priceMode
            );
            $shipmentGroupTransfer->getShipment()->setExpense($shippingExpenseTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param int $shipmentSelectionId
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function findShipmentMethodById(ShipmentMethodsTransfer $shipmentMethodsTransfer, int $shipmentSelectionId): ?ShipmentMethodTransfer
    {
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodsTransfer) {
            if ($shipmentMethodsTransfer->getIdShipmentMethod() === $shipmentSelectionId) {
                return $shipmentMethodsTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    protected function addAvailableShipmentMethods(QuoteTransfer $quoteTransfer): ShipmentGroupCollectionTransfer
    {
        return $this->shipmentClient->getAvailableMethodsByShipment($quoteTransfer);
    }

    /**
     * @param \ArrayObject $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function groupItemsByShipment(ArrayObject $itemTransfers): ArrayObject
    {
        return $this->shipmentService->groupItemsByShipment($itemTransfers);
    }

    /**
     * @param \ArrayObject $shipmentGroupCollection
     *
     * @return \ArrayObject of ShipmentGroupTransfer
     */
    protected function doIndexByHash(ArrayObject $shipmentGroupCollection): ArrayObject
    {
        $shipmentMethodsByShipmentGroupHash = new ArrayObject();
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentGroupTransfer->requireHash();

            $shipmentMethodsByShipmentGroupHash[$shipmentGroupTransfer->getHash()] = $shipmentGroupTransfer;
        }

        return $shipmentMethodsByShipmentGroupHash;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShippingExpenseTransfer(ShipmentMethodTransfer $shipmentMethodTransfer, $priceMode): ExpenseTransfer
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
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }
}
