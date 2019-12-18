<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Widget;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class ProductOfferSoldByMerchantWidget extends AbstractWidget
{
    protected const PARAMETER_MERCHANT_PROFILE = 'merchantProfile';
    protected const PARAMETER_CURRENT_LOCALE = 'currentLocale';

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer|null $productOfferTransfer
     */
    public function __construct(?ProductOfferTransfer $productOfferTransfer)
    {
        if (!$productOfferTransfer) {
            return;
        }

        $this->addMerchantProfileParameter($productOfferTransfer);
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
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return $this
     */
    protected function addMerchantProfileParameter(ProductOfferTransfer $productOfferTransfer)
    {
        $productOfferTransfer->requireProductOfferReference();

        $productOfferStorageTransfer = $this->getFactory()
            ->getMerchantProductOfferStorageClient()
            ->findProductOfferStorageByReference($productOfferTransfer->getProductOfferReference());

        $merchantProfileStorageTransfer = $this->getFactory()
            ->getMerchantProfileStorageClient()
            ->findMerchantProfileStorageData($productOfferStorageTransfer->getIdMerchant());

        $this->addParameter(static::PARAMETER_MERCHANT_PROFILE, $merchantProfileStorageTransfer);

        return $this;
    }
}
