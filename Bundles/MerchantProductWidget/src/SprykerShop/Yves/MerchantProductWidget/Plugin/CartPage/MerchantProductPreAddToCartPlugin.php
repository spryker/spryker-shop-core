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
 * @method \SprykerShop\Yves\MerchantProductWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class MerchantProductPreAddToCartPlugin extends AbstractPlugin implements PreAddToCartPluginInterface
{
    protected const PARAM_MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * {@inheritDoc}
     * - Sets merchant reference to item transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function preAddToCart(ItemTransfer $itemTransfer, array $params): ItemTransfer
    {
        if (!isset($params[static::PARAM_MERCHANT_REFERENCE]) || !$params[static::PARAM_MERCHANT_REFERENCE]) {
            return $itemTransfer;
        }

        $merchantProductTransfer = $this->getFactory()
            ->getMerchantProductStorageClient()
            ->findOne($itemTransfer->getIdProductAbstract());

        if (!$merchantProductTransfer->getMerchantReference() !== $params[static::PARAM_MERCHANT_REFERENCE]) {
            return $itemTransfer;
        }

        $itemTransfer->setMerchantReference($merchantStorageTransfer->getMerchantReference());

        return $itemTransfer;
    }
}
