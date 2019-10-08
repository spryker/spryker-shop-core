<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesConfigurableBundleWidget\Widget;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\SalesConfigurableBundleWidget\SalesConfigurableBundleWidgetFactory getFactory()
 * @method \SprykerShop\Yves\SalesConfigurableBundleWidget\SalesConfigurableBundleWidgetConfig getConfig()
 */
class OrderConfiguredBundleWidget extends AbstractWidget
{
    protected const PARAMETER_ORDER = 'order';
    protected const PARAMETER_ITEMS = 'items';
    protected const PARAMETER_SALES_ORDER_CONFIGURED_BUNDLES = 'salesOrderConfiguredBundles';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[]|null $itemTransfers
     */
    public function __construct(OrderTransfer $orderTransfer, ?iterable $itemTransfers = [])
    {
        if (!count($itemTransfers)) {
            $itemTransfers = $orderTransfer->getItems();
        }

        $itemTransfers = $this->mapOrderItems($itemTransfers);
        $salesOrderConfiguredBundles = $this->mapSalesOrderConfiguredBundles($orderTransfer, $itemTransfers);

        $this->addItemsParameter($itemTransfers);
        $this->addOrderParameter($orderTransfer);
        $this->addSalesOrderConfiguredBundlesParameter($salesOrderConfiguredBundles);
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
        return '@SalesConfigurableBundleWidget/views/order-configured-bundle-widget/order-configured-bundle-widget.twig';
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
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    protected function addItemsParameter(iterable $itemTransfers): void
    {
        $this->addParameter(static::PARAMETER_ITEMS, $itemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundles
     *
     * @return void
     */
    protected function addSalesOrderConfiguredBundlesParameter(iterable $salesOrderConfiguredBundles): void
    {
        $this->addParameter(static::PARAMETER_SALES_ORDER_CONFIGURED_BUNDLES, $salesOrderConfiguredBundles);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function mapOrderItems(iterable $itemTransfers): array
    {
        $items = [];

        foreach ($itemTransfers as $itemTransfer) {
            $items[$itemTransfer->getIdSalesOrderItem()] = $itemTransfer;
        }

        return $items;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[]
     */
    protected function mapSalesOrderConfiguredBundles(OrderTransfer $orderTransfer, iterable $itemTransfers): array
    {
        $salesOrderConfiguredBundles = [];

        foreach ($orderTransfer->getSalesOrderConfiguredBundles() as $salesOrderConfiguredBundleTransfer) {
            if ($this->hasSalesOrderItems($salesOrderConfiguredBundleTransfer, $itemTransfers)) {
                $salesOrderConfiguredBundles[] = $salesOrderConfiguredBundleTransfer;
            }
        }

        return $salesOrderConfiguredBundles;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     *
     * @return int[]
     */
    protected function getSalesOrderItemIdsFromConfiguredBundle(
        SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
    ): array {
        $salesOrderItemIds = [];

        foreach ($salesOrderConfiguredBundleTransfer->getSalesOrderConfiguredBundleItems() as $salesOrderConfiguredBundleItemTransfer) {
            $salesOrderItemIds[] = $salesOrderConfiguredBundleItemTransfer->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    protected function hasSalesOrderItems(
        SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer,
        iterable $itemTransfers
    ): bool {
        foreach ($itemTransfers as $itemTransfer) {
            if (in_array($itemTransfer->getIdSalesOrderItem(), $this->getSalesOrderItemIdsFromConfiguredBundle($salesOrderConfiguredBundleTransfer))) {
                return true;
            }
        }

        return false;
    }
}
