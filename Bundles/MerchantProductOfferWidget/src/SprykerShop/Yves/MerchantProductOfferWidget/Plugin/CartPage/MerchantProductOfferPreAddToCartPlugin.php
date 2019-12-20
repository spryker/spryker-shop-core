<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Plugin\CartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartPageExtension\Dependency\Plugin\PreAddToCartPluginInterface;

/**
 * @method \SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class MerchantProductOfferPreAddToCartPlugin extends AbstractPlugin implements PreAddToCartPluginInterface
{
    protected const PARAM_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * {@inheritDoc}
     * - Sets product offer reference to item transfer.
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
        if (!isset($params[static::PARAM_PRODUCT_OFFER_REFERENCE])) {
            return $itemTransfer;
        }

        $productOfferStorageTransfer = $this->getFactory()
            ->getMerchantProductOfferStorageClient()
            ->findProductOfferStorageByReference($params[static::PARAM_PRODUCT_OFFER_REFERENCE]);

        if (!$productOfferStorageTransfer) {
            return $itemTransfer;
        }

        $merchantProfileStorageTransfer = $this->getFactory()
            ->getMerchantProfileStorageClient()
            ->findMerchantProfileStorageData($productOfferStorageTransfer->getIdMerchant());

        $productOfferTransfer = (new ProductOfferTransfer())
            ->setProductOfferReference($params[static::PARAM_PRODUCT_OFFER_REFERENCE])
            ->setMerchantReference($merchantProfileStorageTransfer->getMerchantReference());

        $itemTransfer->setProductOffer($productOfferTransfer);

        return $itemTransfer;
    }
}
