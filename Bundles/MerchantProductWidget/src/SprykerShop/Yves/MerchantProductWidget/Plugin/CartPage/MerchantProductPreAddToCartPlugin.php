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
    protected const PARAM_MERCHANT_REFERENCE = 'merchant_reference';
    protected const ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';

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
        if (empty($params[static::PARAM_MERCHANT_REFERENCE])) {
            return $itemTransfer;
        }
        $productConcreteStorageData = $this->getFactory()
            ->getProductStorageClient()
            ->findProductConcreteStorageDataByMapping(
                static::PRODUCT_CONCRETE_MAPPING_TYPE,
                $itemTransfer->getSku(),
                $this->getLocale()
            );

        if (!isset($productConcreteStorageData[static::ID_PRODUCT_ABSTRACT])) {
            return $itemTransfer;
        }

        $productAbstractStorageData = $this->getFactory()
            ->getProductStorageClient()
            ->findProductAbstractStorageData(
                $productConcreteStorageData[static::ID_PRODUCT_ABSTRACT],
                $this->getLocale()
            );

        if (
            !isset($productAbstractStorageData[static::PARAM_MERCHANT_REFERENCE]) ||
            $params[static::PARAM_MERCHANT_REFERENCE] !== $productAbstractStorageData[static::PARAM_MERCHANT_REFERENCE]
        ) {
            return $itemTransfer;
        }

        $itemTransfer->setMerchantReference($params[static::PARAM_MERCHANT_REFERENCE]);

        return $itemTransfer;
    }
}
