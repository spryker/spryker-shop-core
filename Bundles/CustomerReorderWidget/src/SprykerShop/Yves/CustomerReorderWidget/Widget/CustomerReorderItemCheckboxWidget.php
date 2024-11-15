<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @deprecated Use {@link \SprykerShop\Yves\CartReorderPage\Widget\CartReorderItemCheckboxWidget} instead.
 *
 * @method \SprykerShop\Yves\CustomerReorderWidget\CustomerReorderWidgetFactory getFactory()
 */
class CustomerReorderItemCheckboxWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_ITEM = 'item';

    /**
     * @var string
     */
    protected const PARAMETER_AVAILABILITY = 'availability';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer
     */
    public function __construct(?ItemTransfer $itemTransfer = null)
    {
        $this->addItemParameter($itemTransfer);
        $this->addAvailabilityParameter($itemTransfer);
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
        return '@CustomerReorderWidget/views/customer-reorder-item-checkbox/customer-reorder-item-checkbox.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer
     *
     * @return void
     */
    protected function addItemParameter(?ItemTransfer $itemTransfer = null): void
    {
        $this->addParameter(static::PARAMETER_ITEM, $itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer
     *
     * @return void
     */
    protected function addAvailabilityParameter(?ItemTransfer $itemTransfer = null): void
    {
        $this->addParameter(static::PARAMETER_AVAILABILITY, $this->isItemAvailable($itemTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer
     *
     * @return bool
     */
    protected function isItemAvailable(?ItemTransfer $itemTransfer = null): bool
    {
        if (!$itemTransfer) {
            return false;
        }

        return $this->getFactory()
            ->createAvailabilityChecker()
            ->checkBySalesItem($itemTransfer);
    }
}
