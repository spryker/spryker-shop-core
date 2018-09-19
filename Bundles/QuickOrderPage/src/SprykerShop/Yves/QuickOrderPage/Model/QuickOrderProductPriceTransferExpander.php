<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Model;

use Generated\Shared\Transfer\QuickOrderProductPriceTransfer;

class QuickOrderProductPriceTransferExpander implements QuickOrderProductPriceTransferExpanderInterface
{
    /**
     * @var \Spryker\Zed\QuickOrderExtension\Dependency\Plugin\QuickOrderProductPriceTransferExpanderPluginInterface[]
     */
    protected $quickOrderProductPriceTransferExpanderPlugins;

    /**
     * @param \Spryker\Zed\QuickOrderExtension\Dependency\Plugin\QuickOrderProductPriceTransferExpanderPluginInterface[] $quickOrderProductPriceTransferExpanderPlugins
     */
    public function __construct(array $quickOrderProductPriceTransferExpanderPlugins)
    {
        $this->quickOrderProductPriceTransferExpanderPlugins = $quickOrderProductPriceTransferExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderProductPriceTransfer
     */
    public function expandQuickOrderProductPriceTransferWithPlugins(QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer): QuickOrderProductPriceTransfer
    {
        foreach ($this->quickOrderProductPriceTransferExpanderPlugins as $quickOrderProductPriceTransferExpanderPlugin) {
            $quickOrderProductPriceTransfer = $quickOrderProductPriceTransferExpanderPlugin->expandQuickOrderProductPriceTransfer($quickOrderProductPriceTransfer);
        }

        return $quickOrderProductPriceTransfer;
    }
}
