<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget;

use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Mapper\MerchantProductViewMapper;
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
            $this->getMerchantProductOfferStorageClient(),
            $this->createShopContextResolver(),
            $this->createMerchantProductViewMapper()
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Mapper\MerchantProductViewMapper
     */
    public function createMerchantProductViewMapper(): MerchantProductViewMapper
    {
        return new MerchantProductViewMapper();
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface
     */
    public function createShopContextResolver(): ShopContextResolverInterface
    {
        return new ShopContextResolver($this->getApplication());
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface[]
     */
    public function getMerchantProductOfferStorageClient(): MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferWidgetDependencyProvider::CLIENT_MERCHANT_PRODUCT_OFFER_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantProductOfferWidgetToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferWidgetDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(MerchantProductOfferWidgetDependencyProvider::PLUGIN_APPLICATION);
    }
}
