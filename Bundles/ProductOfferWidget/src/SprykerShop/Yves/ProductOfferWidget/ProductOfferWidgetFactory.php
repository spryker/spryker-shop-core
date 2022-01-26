<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductOfferWidget\Dependency\Client\ProductOfferWidgetToMerchantProductOfferStorageClientInterface;

/**
 * @method \SprykerShop\Yves\ProductOfferWidget\ProductOfferWidgetConfig getConfig()
 */
class ProductOfferWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductOfferWidget\Dependency\Client\ProductOfferWidgetToMerchantProductOfferStorageClientBridge
     */
    public function getMerchantProductOfferStorageClient(): ProductOfferWidgetToMerchantProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferWidgetDependencyProvider::CLIENT_MERCHANT_PRODUCT_OFFER_STORAGE);
    }
}
