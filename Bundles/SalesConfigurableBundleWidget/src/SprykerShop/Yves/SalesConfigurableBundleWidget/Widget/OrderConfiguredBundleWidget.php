<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesConfigurableBundleWidget\Widget;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @deprecated Use {@link \SprykerShop\Yves\SalesConfigurableBundleWidget\Widget\OrderItemsConfiguredBundleWidget} instead.
 *
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
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[]|null $itemTransfers
     */
    public function __construct(OrderTransfer $orderTransfer, ?iterable $itemTransfers = [])
    {
        if ($itemTransfers === null || !count($itemTransfers)) {
            $itemTransfers = $orderTransfer->getItems();
        }

        $itemTransfers = $this->mapOrderItems($itemTransfers);

        $salesOrderConfiguredBundles = $this->getFactory()
            ->createSalesOrderConfiguredBundleGrouper()
            ->getSalesOrderConfiguredBundles($orderTransfer, $itemTransfers);

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
        return '@SalesConfigurableBundleWidget/views/order-detail-configured-bundle-widget/order-detail-configured-bundle-widget.twig';
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
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    protected function addItemsParameter(iterable $itemTransfers): void
    {
        $this->addParameter(static::PARAMETER_ITEMS, $itemTransfers);
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundles
     *
     * @return void
     */
    protected function addSalesOrderConfiguredBundlesParameter(iterable $salesOrderConfiguredBundles): void
    {
        $this->addParameter(static::PARAMETER_SALES_ORDER_CONFIGURED_BUNDLES, $salesOrderConfiguredBundles);
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
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
}
