<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface;

class PostConditionChecker implements PostConditionCheckerInterface
{
    protected const SHIPMENT_TRANSFER_KEY_PATTERN = '%s-%s-%s';

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface $shipmentService
     */
    public function __construct(CheckoutPageToShipmentServiceInterface $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(AbstractTransfer $quoteTransfer): bool
    {
        if ($this->hasOnlyGiftCardItems($quoteTransfer)) {
            return true;
        }

        return $this->isShipmentSet($quoteTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShipmentSet(QuoteTransfer $quoteTransfer): bool
    {
        if ($quoteTransfer->getItems()->count() === 0) {
            return false;
        }

        foreach ($quoteTransfer->getShipmentGroups() as $shipmentGroupTransfer) {
            if (!$this->checkShipmentExpenseSetInQuote($quoteTransfer, $shipmentGroupTransfer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return bool
     */
    protected function checkShipmentExpenseSetInQuote(QuoteTransfer $quoteTransfer, ShipmentGroupTransfer $shipmentGroupTransfer): bool
    {
        $shipmentTransfer = $shipmentGroupTransfer->getShipment();
        if ($shipmentTransfer === null) {
            return false;
        }

        $itemShipmentKey = $this->shipmentService->getShipmentHashKey($shipmentTransfer);
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($this->checkShipmentExpenseKey($expenseTransfer, $itemShipmentKey)) {
                return $shipmentTransfer->getShipmentSelection() !== null;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasOnlyGiftCardItems(QuoteTransfer $quoteTransfer): bool
    {
        $onlyGiftCardItems = true;
        foreach ($quoteTransfer->getItems() as $item) {
            $giftCardMetadataTransfer = $item->getGiftCardMetadata();
            $isGiftCard = $giftCardMetadataTransfer !== null ? $giftCardMetadataTransfer->getIsGiftCard() : false;
            $onlyGiftCardItems &= $isGiftCard;
        }

        return (bool)$onlyGiftCardItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param string $itemShipmentKey
     *
     * @return bool
     */
    protected function checkShipmentExpenseKey(ExpenseTransfer $expenseTransfer, string $itemShipmentKey): bool
    {
        return $expenseTransfer->getType() === ShipmentConstants::SHIPMENT_EXPENSE_TYPE
            && $this->shipmentService
                ->isShipmentEqualShipmentHashKey($expenseTransfer->requireShipment()->getShipment(), $itemShipmentKey);
    }
}
