<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Plugin\ShopApplication;

use Generated\Shared\Transfer\ShopContextTransfer;
use Spryker\Shared\ShopContextExtension\Dependency\Plugin\ShopContextExpanderPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetFactory getFactory()
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig getConfig()
 */
class MerchantShopContextExpanderPlugin extends AbstractPlugin implements ShopContextExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands selected merchant reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopContextTransfer $shopContextTransfer
     *
     * @return \Generated\Shared\Transfer\ShopContextTransfer
     */
    public function expand(ShopContextTransfer $shopContextTransfer): ShopContextTransfer
    {
        if (!$this->getConfig()->isMerchantSwitcherEnabled()) {
            return $shopContextTransfer;
        }

        $selectedMerchantReference = $this->getFactory()
            ->createMerchantReader()
            ->extractSelectedMerchantReference();

        $shopContextTransfer->setMerchantReference($selectedMerchantReference);

        return $shopContextTransfer;
    }
}
