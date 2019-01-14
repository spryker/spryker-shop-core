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
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * ShipmentHandler constructor.
     *
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface $shipmentClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPriceClientInterface $priceClient
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     */
    public function __construct(
        CheckoutPageToShipmentClientInterface $shipmentClient,
        CheckoutPageToPriceClientInterface $priceClient,
        ShipmentServiceInterface $shipmentService
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
        $quoteTransfer->setShipmentGroups($this->getShipmentGroupCollection($quoteTransfer));
        $this->addAvailableShipmentMethods($quoteTransfer);

        foreach ($quoteTransfer->getShipmentGroups() as $shipmentGroupTransfer){
            foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
                $shipmentTransfer = $itemTransfer->getShipment();
                $shipmentTransfer->setShipmentSelection(
                    $shipmentGroupTransfer->getShipment()->getShipmentSelection()
                );
                $shipmentMethodTransfer = $this->getShipmentMethodById(
                    $shipmentGroupTransfer->getAvailableShipmentMethods(),
                    $shipmentTransfer->getShipmentSelection()
                );
                $shipmentTransfer->setMethod($shipmentMethodTransfer);
                $shipmentTransfer->setExpense(
                    $this->createShippingExpenseTransfer($shipmentMethodTransfer, $quoteTransfer->getPriceMode())
                );
                $shipmentMethodTransfer->setFkShipmentCarrier($shipmentMethodTransfer->getFkShipmentCarrier());
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addAvailableShipmentMethods(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $shipmentGroupCollectionTransfer = $this->getAvailableShipmentMethodsByShipment($quoteTransfer);
        $shipmentMethodsByHash = $this->mapShipmentMethodsByShipmentGroupHash($shipmentGroupCollectionTransfer);
        foreach ($quoteTransfer->getShipmentGroups() as $shipmentGroupTransfer) {
            $shipmentGroupTransfer->setAvailableShipmentMethods(
                $shipmentMethodsByHash[$shipmentGroupTransfer->getHash()]
            );
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function getShipmentMethodById(ShipmentMethodsTransfer $shipmentMethodsTransfer, int $shipmentSelectionId): ShipmentMethodTransfer
    {
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodsTransfer) {
            if ($shipmentMethodsTransfer->getIdShipmentMethod() === $shipmentSelectionId) {
                return $shipmentMethodsTransfer;
            }
        }

        return null;
    }

    /**
     * @param \ArrayObject $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    protected function getAvailableShipmentMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentGroupCollectionTransfer
    {
        return $this->shipmentClient->getAvailableMethodsByShipment($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function getShipmentGroupCollection(QuoteTransfer $quoteTransfer): ArrayObject
    {
        return $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer $shipmentGroupCollectionTransfer
     *
     * @return \ArrayObject
     */
    protected function mapShipmentMethodsByShipmentGroupHash(
        ShipmentGroupCollectionTransfer $shipmentGroupCollectionTransfer
    ): ArrayObject {
        $shipmentMethodsByShipmentGroupHash = new ArrayObject();
        foreach ($shipmentGroupCollectionTransfer->getGroups() as $shipmentGroupTransfer) {
            $shipmentGroupTransfer->requireAvailableShipmentMethods();
            $shipmentGroupTransfer->requireHash();

            $shipmentMethodsByShipmentGroupHash[$shipmentGroupTransfer->getHash()] = $shipmentGroupTransfer->getAvailableShipmentMethods();
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
