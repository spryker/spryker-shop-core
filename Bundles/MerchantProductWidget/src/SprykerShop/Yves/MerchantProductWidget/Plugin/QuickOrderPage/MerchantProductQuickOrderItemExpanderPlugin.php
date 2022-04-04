<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Plugin\QuickOrderPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\MerchantProductWidget\MerchantProductWidgetFactory getFactory()
 */
class MerchantProductQuickOrderItemExpanderPlugin extends AbstractPlugin implements QuickOrderItemExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands provided ItemTransfer with additional data.
     * - Executed before adding quick order items into cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItem(ItemTransfer $itemTransfer): ItemTransfer
    {
        /** @var string $locale */
        $locale = $this->getLocale();

        return $this->getFactory()
            ->createMerchantProductQuickOrderItemExpander()
            ->expandItem($itemTransfer, $locale);
    }
}
