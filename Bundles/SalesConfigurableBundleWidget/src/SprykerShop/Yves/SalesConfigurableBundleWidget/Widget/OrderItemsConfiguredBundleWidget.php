<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesConfigurableBundleWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\SalesConfigurableBundleWidget\SalesConfigurableBundleWidgetFactory getFactory()
 * @method \SprykerShop\Yves\SalesConfigurableBundleWidget\SalesConfigurableBundleWidgetConfig getConfig()
 */
class OrderItemsConfiguredBundleWidget extends AbstractWidget
{
    protected const PARAMETER_ITEMS = 'items';
    protected const PARAMETER_SALES_ORDER_CONFIGURED_BUNDLES = 'salesOrderConfiguredBundles';

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     */
    public function __construct(iterable $itemTransfers)
    {
        $itemTransfers = $this->getItemsIndexedByIdSalesOrderItem($itemTransfers);

        $salesOrderConfiguredBundles = $this->getFactory()
            ->createSalesOrderConfiguredBundleGrouper()
            ->getSalesOrderConfiguredBundlesByItems($itemTransfers);

        $this->addItemsParameter($itemTransfers);
        $this->addSalesOrderConfiguredBundlesParameter($salesOrderConfiguredBundles);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'OrderItemsConfiguredBundleWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SalesConfigurableBundleWidget/views/order-items-configured-bundle-widget/order-items-configured-bundle-widget.twig';
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
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundles
     *
     * @return void
     */
    protected function addSalesOrderConfiguredBundlesParameter(array $salesOrderConfiguredBundles): void
    {
        $this->addParameter(static::PARAMETER_SALES_ORDER_CONFIGURED_BUNDLES, $salesOrderConfiguredBundles);
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getItemsIndexedByIdSalesOrderItem(iterable $itemTransfers): array
    {
        $indexedItems = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getIdSalesOrderItem()) {
                $indexedItems[$itemTransfer->getIdSalesOrderItem()] = $itemTransfer;
            }
        }

        return $indexedItems;
    }
}
