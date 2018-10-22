<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\PluginExecutor;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;

class QuickOrderItemPluginExecutor implements QuickOrderItemPluginExecutorInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemFilterPluginInterface[]
     */
    protected $quickOrderItemFilterPlugins;

    /**
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemFilterPluginInterface[] $quickOrderItemFilterPlugins
     */
    public function __construct(array $quickOrderItemFilterPlugins)
    {
        $this->quickOrderItemFilterPlugins = $quickOrderItemFilterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $products
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function applyQuickOrderItemFilterPluginsOnQuickOrder(QuickOrderTransfer $quickOrderTransfer, array $products): QuickOrderTransfer
    {
        $quickOrderItems = [];
        foreach ($quickOrderTransfer->getItems() as $quickOrderItemTransfer) {
            $quickOrderItems[] = $this->applyQuickOrderItemFilterPluginsOnQuickOrderItem($quickOrderItemTransfer, $products[$quickOrderItemTransfer->getSku()] ?? null);
        }
        $quickOrderTransfer->setItems(new \ArrayObject($quickOrderItems));

        return $quickOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer|null $product
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function applyQuickOrderItemFilterPluginsOnQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer, ?ProductConcreteTransfer $product): QuickOrderItemTransfer
    {
        if (empty($quickOrderItemTransfer->getSku())) {
            return $quickOrderItemTransfer;
        }

        if (empty($product)) {
            return $quickOrderItemTransfer;
        }

        foreach ($this->quickOrderItemFilterPlugins as $quickOrderItemFilterPlugin) {
            $quickOrderItemTransfer = $quickOrderItemFilterPlugin->filterItem($quickOrderItemTransfer, $product);
        }

        return $quickOrderItemTransfer;
    }
}
