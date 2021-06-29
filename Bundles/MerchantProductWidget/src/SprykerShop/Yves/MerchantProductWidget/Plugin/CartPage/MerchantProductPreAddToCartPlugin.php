<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Plugin\CartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartPageExtension\Dependency\Plugin\PreAddToCartPluginInterface;

/**
 * @method \SprykerShop\Yves\MerchantProductWidget\MerchantProductWidgetFactory getFactory()
 */
class MerchantProductPreAddToCartPlugin extends AbstractPlugin implements PreAddToCartPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets merchant reference to item transfer.
     *
     * @api
     *
     * @phpstan-param array<string> $params
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function preAddToCart(ItemTransfer $itemTransfer, array $params): ItemTransfer
    {
        /** @var string $locale */
        $locale = $this->getLocale();

        return $this->getFactory()
            ->createMerchantProductExpander()
            ->expandItemTransferWithMerchantReference($itemTransfer, $params, $locale);
    }
}
