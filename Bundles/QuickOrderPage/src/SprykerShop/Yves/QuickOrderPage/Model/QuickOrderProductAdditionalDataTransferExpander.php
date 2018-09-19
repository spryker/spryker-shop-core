<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Model;

use Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer;

class QuickOrderProductAdditionalDataTransferExpander implements QuickOrderProductAdditionalDataTransferExpanderInterface
{
    /**
     * @var \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderProductAdditionalDataTransferExpanderPluginInterface[]
     */
    protected $quickOrderProductAdditionalDataTransferExpanderPlugins;

    /**
     * @param \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderProductAdditionalDataTransferExpanderPluginInterface[] $quickOrderProductAdditionalDataTransferExpanderPlugins
     */
    public function __construct(array $quickOrderProductAdditionalDataTransferExpanderPlugins)
    {
        $this->quickOrderProductAdditionalDataTransferExpanderPlugins = $quickOrderProductAdditionalDataTransferExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer $quickOrderProductAdditionalDataTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer
     */
    public function expandQuickOrderProductAdditionalDataTransferWithPlugins(QuickOrderProductAdditionalDataTransfer $quickOrderProductAdditionalDataTransfer): QuickOrderProductAdditionalDataTransfer
    {
        foreach ($this->quickOrderProductAdditionalDataTransferExpanderPlugins as $quickOrderProductAdditionalDataTransferExpanderPlugin) {
            $quickOrderProductAdditionalDataTransfer = $quickOrderProductAdditionalDataTransferExpanderPlugin->expandQuickOrderProductAdditionalDataTransfer($quickOrderProductAdditionalDataTransfer);
        }

        return $quickOrderProductAdditionalDataTransfer;
    }
}
