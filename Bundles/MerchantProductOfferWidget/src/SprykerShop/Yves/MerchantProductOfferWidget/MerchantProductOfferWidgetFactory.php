<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget;

use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProfileStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToPriceProductStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Service\MerchantProductOfferWidgetToPriceProductClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReader;
use SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReaderInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolver;
use SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface;

class MerchantProductOfferWidgetFactory extends AbstractFactory
{
    protected const SERVICE_SHOP_CONTEXT = 'SERVICE_SHOP_CONTEXT';

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReaderInterface
     */
    public function createProductOfferReader(): MerchantProductOfferReaderInterface
    {
        return new MerchantProductOfferReader(
            $this->getMerchantProfileStorageClient(),
            $this->getMerchantProductOfferStorageClient(),
            $this->getPriceProductServiceClient(),
            $this->getPriceProductStorageClient(),
            $this->createShopContextResolver()
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface
     */
    public function getMerchantProductOfferStorageClient(): MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferWidgetDependencyProvider::CLIENT_MERCHANT_PRODUCT_OFFER_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProfileStorageClientInterface
     */
    public function getMerchantProfileStorageClient(): MerchantProductOfferWidgetToMerchantProfileStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferWidgetDependencyProvider::CLIENT_MERCHANT_PROFILE_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Service\MerchantProductOfferWidgetToPriceProductClientInterface
     */
    public function getPriceProductServiceClient(): MerchantProductOfferWidgetToPriceProductClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferWidgetDependencyProvider::CLIENT_PRICE_PRODUCT_SERVICE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): MerchantProductOfferWidgetToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferWidgetDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(MerchantProductOfferWidgetDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface
     */
    public function createShopContextResolver(): ShopContextResolverInterface
    {
        return new ShopContextResolver($this->getApplication());
    }
}
