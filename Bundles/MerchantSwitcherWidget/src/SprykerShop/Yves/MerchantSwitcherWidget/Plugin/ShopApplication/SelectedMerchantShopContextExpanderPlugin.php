<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Plugin\ShopApplication;

use Generated\Shared\Transfer\ShopContextTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopContextExtension\Dependency\Plugin\ShopContextExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetFactory getFactory()
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig getConfig()
 */
class SelectedMerchantShopContextExpanderPlugin extends AbstractPlugin implements ShopContextExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ShopContextTransfer with merchant reference from cookie.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopContextTransfer $shopContextTransfer
     *
     * @return \Generated\Shared\Transfer\ShopContextTransfer
     */
    public function expand(ShopContextTransfer $shopContextTransfer): ShopContextTransfer
    {
        $merchantReference = $this->getFactory()->getRequest()->cookies->get(
            $this->getConfig()->getMerchantSelectorCookieIdentifier()
        );
        $shopContextTransfer->setMerchantReference($merchantReference);

        return $shopContextTransfer;
    }
}
