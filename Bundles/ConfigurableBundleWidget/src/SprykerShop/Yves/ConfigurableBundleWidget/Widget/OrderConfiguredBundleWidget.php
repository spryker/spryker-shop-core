<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Widget;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetConfig getConfig()
 */
class OrderConfiguredBundleWidget extends AbstractWidget
{
    protected const PARAMETER_ORDER = 'order';
    protected const PARAMETER_ITEMS = 'items';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     */
    public function __construct(OrderTransfer $orderTransfer)
    {
        $this->addOrderParameter($orderTransfer);
        $this->addItemsParameter($orderTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'OrderConfiguredBundleWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ConfigurableBundleWidget/views/order-configured-bundle-widget/order-configured-bundle-widget.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addOrderParameter(OrderTransfer $orderTransfer): void
    {
        $this->addParameter(static::PARAMETER_ORDER, $orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addItemsParameter(OrderTransfer $orderTransfer): void
    {
        $this->addParameter(static::PARAMETER_ITEMS, $this->mapOrderItems($orderTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function mapOrderItems(OrderTransfer $orderTransfer): array
    {
        $items = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $items[$itemTransfer->getIdSalesOrderItem()] = $itemTransfer;
        }

        return $items;
    }
}
