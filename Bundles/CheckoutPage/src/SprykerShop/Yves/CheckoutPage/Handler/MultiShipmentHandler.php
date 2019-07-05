<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Handler;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPriceClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class MultiShipmentHandler extends ShipmentHandler
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
        $shipmentGroupCollection = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());

        $shipmentGroupCollection = $this->updateShipmentGroupItemsShipment($shipmentGroupCollection);
        $quoteTransfer = $this->updateQuoteItemsWithShipmentGroupsItems($quoteTransfer, $shipmentGroupCollection);

        $shipmentGroupCollection = $this->setAvailableShipmentMethodsToShipmentGroups($quoteTransfer, $shipmentGroupCollection);
        $shipmentGroupCollection = $this->setShipmentGroupsSelectedMethodTransfer($shipmentGroupCollection);
        $quoteTransfer = $this->setShipmentExpenseTransfers($quoteTransfer, $shipmentGroupCollection);

        $quoteTransfer = $this->updateQuoteLevelShipment($quoteTransfer, $shipmentGroupCollection);

        return $quoteTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function updateShipmentGroupItemsShipment(ArrayObject $shipmentGroupCollection): ArrayObject
    {
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentTransfer = $shipmentGroupTransfer->getShipment();
            foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
                $itemTransfer->setShipment($shipmentTransfer);
            }
        }

        return $shipmentGroupCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuoteItemsWithShipmentGroupsItems(
        QuoteTransfer $quoteTransfer,
        ArrayObject $shipmentGroupCollection
    ): QuoteTransfer {
        $quoteItemsCollection = new ArrayObject();

        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
                $quoteItemsCollection->append($itemTransfer);
            }
        }

        $quoteTransfer->setItems($quoteItemsCollection);

        return $quoteTransfer;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransferCollection
     *
     * @return iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function groupItemsByShipment(iterable $itemTransferCollection): iterable
    {
        return $this->shipmentService->groupItemsByShipment($itemTransferCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer
     */
    protected function getAvailableShipmentMethodsGroupedByShipment(QuoteTransfer $quoteTransfer): ShipmentMethodsCollectionTransfer
    {
        return $this->shipmentClient->getAvailableMethodsByShipment($quoteTransfer);
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     * @param \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer $availableShipmentMethodsGroupedByShipment
     *
     * @return iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function setShipmentMethodsToShipmentGroups(
        iterable $shipmentGroupCollection,
        ShipmentMethodsCollectionTransfer $availableShipmentMethodsGroupedByShipment
    ): iterable {
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $availableShipmentMethodsTransfer = $this->findAvailableShipmentMethodsByShipmentGroup(
                $availableShipmentMethodsGroupedByShipment,
                $shipmentGroupTransfer
            );

            if ($availableShipmentMethodsTransfer === null) {
                continue;
            }

            $shipmentGroupTransfer->setAvailableShipmentMethods($availableShipmentMethodsTransfer);
        }

        return $shipmentGroupCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function setAvailableShipmentMethodsToShipmentGroups(
        QuoteTransfer $quoteTransfer,
        iterable $shipmentGroupCollection
    ): iterable {
        $availableShipmentMethodsGroupedByShipment = $this->getAvailableShipmentMethodsGroupedByShipment($quoteTransfer);

        return $this->setShipmentMethodsToShipmentGroups(
            $shipmentGroupCollection,
            $availableShipmentMethodsGroupedByShipment
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer $availableShipmentMethodsGroupedByShipment
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer|null
     */
    protected function findAvailableShipmentMethodsByShipmentGroup(
        ShipmentMethodsCollectionTransfer $availableShipmentMethodsGroupedByShipment,
        ShipmentGroupTransfer $shipmentGroupTransfer
    ): ?ShipmentMethodsTransfer {
        $shipmentHashKey = $shipmentGroupTransfer->requireHash()->getHash();

        foreach ($availableShipmentMethodsGroupedByShipment->getShipmentMethods() as $shipmentMethodsTransfer) {
            if ($shipmentHashKey === $shipmentMethodsTransfer->getShipmentHash()) {
                return $shipmentMethodsTransfer;
            }
        }

        return null;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function setShipmentGroupsSelectedMethodTransfer(iterable $shipmentGroupCollection): iterable
    {
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentTransfer = $shipmentGroupTransfer->requireShipment()
                ->getShipment()
                ->requireShipmentSelection();

            $shipmentMethodTransfer = $this->findShipmentMethodById(
                $shipmentGroupTransfer->getAvailableShipmentMethods(),
                (int)$shipmentTransfer->getShipmentSelection()
            );
            $shipmentTransfer->setMethod($shipmentMethodTransfer);
        }

        return $shipmentGroupCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function findShipmentMethodById(
        ShipmentMethodsTransfer $shipmentMethodsTransfer,
        int $idShipmentMethod
    ): ?ShipmentMethodTransfer {
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getIdShipmentMethod() === $idShipmentMethod) {
                return $shipmentMethodTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setShipmentExpenseTransfers(QuoteTransfer $quoteTransfer, iterable $shipmentGroupCollection): QuoteTransfer
    {
        $priceMode = $quoteTransfer->getPriceMode();
        $quoteTransfer = $this->removeAllShipmentExpensesFromQuote($quoteTransfer);

        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentGroupTransfer->requireShipment();
            $shipmentTransfer = $shipmentGroupTransfer->getShipment()->requireMethod();

            $shipmentExpenseTransfer = $this->createShippingExpenseTransfer($shipmentTransfer->getMethod(), $priceMode);
            $shipmentExpenseTransfer->setShipment($shipmentTransfer);

            $shipmentExpenseKey = $shipmentGroupTransfer->getHash();
            if ($quoteTransfer->getExpenses()->offsetExists($shipmentExpenseKey)) {
                continue;
            }

            $quoteTransfer->getExpenses()
                ->offsetSet($shipmentExpenseKey, $shipmentExpenseTransfer);
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
        $quoteExpenseForRemoveIndexes = [];
        foreach ($quoteTransfer->getExpenses() as $expenseTransferIndex => $expenseTransfer) {
            if ($expenseTransfer->getType() === $this->shipmentService->getShipmentExpenseType()) {
                $quoteExpenseForRemoveIndexes[] = $expenseTransferIndex;
            }
        }

        foreach ($quoteExpenseForRemoveIndexes as $quoteExpenseForRemoveIndex) {
            $quoteTransfer->getExpenses()->offsetUnset($quoteExpenseForRemoveIndex);
        }

        return $quoteTransfer;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuoteLevelShipment(QuoteTransfer $quoteTransfer, iterable $shipmentGroupCollection): QuoteTransfer
    {
        if (count($shipmentGroupCollection) > 1) {
            return $quoteTransfer->setShipment(null);
        }

        $firstShipmentGroup = current($shipmentGroupCollection);
        $firstShipmentGroup->requireShipment();

        return $quoteTransfer->setShipment($firstShipmentGroup->getShipment());
    }
}
