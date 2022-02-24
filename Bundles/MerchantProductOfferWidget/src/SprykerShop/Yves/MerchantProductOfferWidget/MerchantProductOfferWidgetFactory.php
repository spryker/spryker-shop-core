<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToProductOfferStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Expander\MerchantProductOfferExpander;
use SprykerShop\Yves\MerchantProductOfferWidget\Expander\MerchantProductOfferExpanderInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReader;
use SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReaderInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolver;
use SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface;

class MerchantProductOfferWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReaderInterface
     */
    public function createProductOfferReader(): MerchantProductOfferReaderInterface
    {
        return new MerchantProductOfferReader(
            $this->getProductOfferStorageClient(),
            $this->createShopContextResolver(),
            $this->getMerchantStorageClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Expander\MerchantProductOfferExpanderInterface
     */
    public function createMerchantProductOfferExpander(): MerchantProductOfferExpanderInterface
    {
        return new MerchantProductOfferExpander(
            $this->createProductOfferReader(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface
     */
    public function createShopContextResolver(): ShopContextResolverInterface
    {
        return new ShopContextResolver($this->getContainer());
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToProductOfferStorageClientInterface
     */
    public function getProductOfferStorageClient(): MerchantProductOfferWidgetToProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferWidgetDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantProductOfferWidgetToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferWidgetDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }
}
