<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CustomerReorderWidget\CustomerReorderWidgetFactory getFactory()
 */
class CustomerReorderWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer
     */
    public function __construct(OrderTransfer $orderTransfer, ?ItemTransfer $itemTransfer = null)
    {
        $this->addParameter('order', $orderTransfer)
            ->addParameter('item', $itemTransfer)
            ->addParameter('availability', $this->getItemAvailability($itemTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CustomerReorderWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        // TODO: this template has no body block, it contains blocks that used to be rendered with widgetBlock() function.
        return '@CustomerReorderWidget/views/customer-reorder/customer-reorder.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer
     *
     * @return bool
     */
    protected function getItemAvailability(?ItemTransfer $itemTransfer = null): bool
    {
        if (!$itemTransfer) {
            return false;
        }

        $availability = $this->getFactory()
            ->createAvailabilityChecker()
            ->checkBySalesItem($itemTransfer);

        return $availability;
    }
}
