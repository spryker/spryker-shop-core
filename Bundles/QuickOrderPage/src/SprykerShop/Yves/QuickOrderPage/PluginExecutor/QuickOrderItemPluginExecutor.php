<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\PluginExecutor;

use ArrayObject;
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
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function applyQuickOrderItemFilterPluginsOnQuickOrder(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        $quickOrderItems = [];
        foreach ($quickOrderTransfer->getItems() as $quickOrderItemTransfer) {
            $quickOrderItems[] = $this->applyQuickOrderItemFilterPluginsOnQuickOrderItem($quickOrderItemTransfer);
        }
        $quickOrderTransfer->setItems(new ArrayObject($quickOrderItems));

        return $quickOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function applyQuickOrderItemFilterPluginsOnQuickOrderItem(
        QuickOrderItemTransfer $quickOrderItemTransfer
    ): QuickOrderItemTransfer {
        if (!$quickOrderItemTransfer->getSku()) {
            return $quickOrderItemTransfer;
        }

        foreach ($this->quickOrderItemFilterPlugins as $quickOrderItemFilterPlugin) {
            $quickOrderItemTransfer = $quickOrderItemFilterPlugin->filterItem($quickOrderItemTransfer);
        }

        return $quickOrderItemTransfer;
    }
}
