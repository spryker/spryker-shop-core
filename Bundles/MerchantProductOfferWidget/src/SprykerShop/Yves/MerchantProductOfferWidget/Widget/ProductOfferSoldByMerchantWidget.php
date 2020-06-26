<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class ProductOfferSoldByMerchantWidget extends AbstractWidget
{
    protected const PARAMETER_MERCHANT = 'merchant';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     */
    public function __construct(ItemTransfer $itemTransfer)
    {
        $this->addMerchantParameter($itemTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductOfferSoldByMerchantWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantProductOfferWidget/views/product-offer-sold-by-merchant/product-offer-sold-by-merchant.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return $this
     */
    protected function addMerchantParameter(ItemTransfer $itemTransfer)
    {
        $merchantStorageTransfer = null;

        if ($itemTransfer->getProductOfferReference()) {
            $productOfferStorageTransfer = $this->getFactory()
                ->getMerchantProductOfferStorageClient()
                ->findProductOfferStorageByReference($itemTransfer->getProductOfferReference());

            if ($productOfferStorageTransfer) {
                $merchantStorageTransfer = $this->getFactory()
                    ->getMerchantStorageClient()
                    ->findOne($productOfferStorageTransfer->getIdMerchant());
            }
        }

        if ($itemTransfer->getMerchantReference()) {
            $merchantStorageTransfer = $this->getFactory()
                ->getMerchantStorageClient()
                ->findOneByMerchantReference($itemTransfer->getMerchantReference());
        }

        $this->addParameter(static::PARAMETER_MERCHANT, $merchantStorageTransfer);

        return $this;
    }
}
