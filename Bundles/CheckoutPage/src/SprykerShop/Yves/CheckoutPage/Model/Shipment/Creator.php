<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Model\Shipment;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
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
        $quoteTransfer = $this->updateQuoteShipmentGroupsUsingHash($quoteTransfer);

        $availableShipmentMethodsGroupedByShipment = $this->getAvailableMethodsByShipment($quoteTransfer)->getGroups();
        $quoteTransfer = $this->setShipmentMethodsToQuoteShipmentGroups($quoteTransfer, $availableShipmentMethodsGroupedByShipment);

        $this->setShipmentGroupsSelectedMethodTransfer($quoteTransfer->getShipmentGroups());
        $quoteTransfer = $this->setShipmentExpenseTransfers($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuoteShipmentGroupsUsingHash(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $shipmentGroupsCollection = $this->groupItemsByShipment($quoteTransfer->getItems());
        $quoteShipmentGroupCollection = $quoteTransfer->getShipmentGroups();

        foreach ($shipmentGroupsCollection as $shipmentGroupTransfer) {
            foreach ($quoteShipmentGroupCollection as $quoteShipmentGroupTransfer) {
                if ($shipmentGroupTransfer->getHash() !== $quoteShipmentGroupTransfer->getHash()) {
                    continue;
                }

                $shipmentGroupTransfer->setShipment($quoteShipmentGroupTransfer->getShipment());
                break;
            }

            foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
                $itemTransfer->setShipment($shipmentGroupTransfer->getShipment());
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
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getIdShipmentMethod() === $idShipmentMethod) {
                return $shipmentMethodTransfer;
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

            $shipmentTransfer = $shipmentGroupTransfer->getShipment();
            $shipmentExpenseTransfer = $this->createShippingExpenseTransfer($shipmentTransfer->getMethod(), $priceMode);
            $shipmentExpenseTransfer->setShipment($shipmentTransfer);

            $expenseShipmentKey = $this->shipmentService->getShipmentHashKey($shipmentTransfer);
            if ($quoteTransfer->getExpenses()->offsetExists($expenseShipmentKey)) {
                continue;
            }

            $quoteTransfer->getExpenses()->offsetSet($expenseShipmentKey, $shipmentExpenseTransfer);
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
        foreach ($quoteTransfer->getExpenses() as $index => $expenseTransfer) {
            if ($expenseTransfer->getType() !== ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $quoteTransfer->getExpenses()->offsetUnset($index);
        }

        return $quoteTransfer;
    }
}
