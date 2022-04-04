<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Plugin\QuickOrderPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class MerchantProductOfferQuickOrderItemExpanderPlugin extends AbstractPlugin implements QuickOrderItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Finds ProductOfferStorage transfer by ProductOffer reference.
     * - Expands provided ItemTransfer with ProductOfferStorage merchant reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItem(ItemTransfer $itemTransfer): ItemTransfer
    {
        return $this->getFactory()
            ->createMerchantProductOfferQuickOrderItemExpander()
            ->expandItem($itemTransfer);
    }
}
