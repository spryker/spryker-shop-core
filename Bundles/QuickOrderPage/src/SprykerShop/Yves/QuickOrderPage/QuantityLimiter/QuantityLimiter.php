<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\QuantityLimiter;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig;

class QuantityLimiter implements QuantityLimiterInterface
{
    protected const ERROR_MESSAGE_QUANTITY_INVALID = 'quick-order.errors.quantity-invalid';
    protected const MESSAGE_TYPE_WARNING = 'warning';

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig
     */
    protected $quickOrderPageConfig;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig $quickOrderPageConfig
     */
    public function __construct(QuickOrderPageConfig $quickOrderPageConfig)
    {
        $this->quickOrderPageConfig = $quickOrderPageConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function limitQuickOrderItemsQuantity(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        foreach ($quickOrderTransfer->getItems() as $quickOrderItemTransfer) {
            if (!$quickOrderItemTransfer->getSku()) {
                continue;
            }

            $this->limitQuickOrderItemTransferQuantity($quickOrderItemTransfer);
        }

        return $quickOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return void
     */
    protected function limitQuickOrderItemTransferQuantity(QuickOrderItemTransfer $quickOrderItemTransfer): void
    {
        /**
         * @var string|null $originalQuantity
         */
        $originalQuantity = $quickOrderItemTransfer->getQuantity();
        $integerQuantiy = (int)$originalQuantity;
        $maxAllowedQuantity = $this->quickOrderPageConfig->getMaxAllowedQuantity();

        if ($originalQuantity === null) {
            $quickOrderItemTransfer->setQuantity(1);

            return;
        }

        if ($integerQuantiy < 1) {
            $this->adjustQuantity($quickOrderItemTransfer, 1);

            return;
        }

        if ((string)$integerQuantiy !== $originalQuantity || $integerQuantiy > $maxAllowedQuantity) {
            $this->adjustQuantity($quickOrderItemTransfer, $maxAllowedQuantity);

            return;
        }

        $quickOrderItemTransfer->setQuantity($integerQuantiy);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     * @param int $newQuantity
     *
     * @return void
     */
    protected function adjustQuantity(QuickOrderItemTransfer $quickOrderItemTransfer, int $newQuantity): void
    {
        $quickOrderItemTransfer->setQuantity($newQuantity)
            ->addMessage($this->createInvalidQuantityMessageTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createInvalidQuantityMessageTransfer(): MessageTransfer
    {
        return (new MessageTransfer())
            ->setType(static::MESSAGE_TYPE_WARNING)
            ->setValue(static::ERROR_MESSAGE_QUANTITY_INVALID);
    }
}
