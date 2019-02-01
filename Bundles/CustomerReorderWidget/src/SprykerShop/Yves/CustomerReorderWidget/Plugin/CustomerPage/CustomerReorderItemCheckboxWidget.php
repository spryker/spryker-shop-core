<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CustomerReorderWidget\CustomerReorderWidgetFactory getFactory()
 */
class CustomerReorderItemCheckboxWidget extends AbstractWidget
{
    /**
     * @var \Generated\Shared\Transfer\ItemTransfer
     */
    private $itemTransfer;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer
     */
    public function __construct(ItemTransfer $itemTransfer = null)
    {
        $this
            ->addParameter('item', $itemTransfer)
            ->addParameter('availability', $this->getItemAvailability($itemTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CustomerReorderItemCheckboxWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CustomerReorderWidget/views/customer-reorder/customer-reorder-item-check-box.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer
     *
     * @return bool
     */
    protected function getItemAvailability(ItemTransfer $itemTransfer = null): bool
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
