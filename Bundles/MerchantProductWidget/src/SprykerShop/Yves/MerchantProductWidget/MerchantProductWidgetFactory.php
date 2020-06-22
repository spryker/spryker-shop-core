<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantProductOfferWidget\Sorter\MerchantProductViewCollectionSorter;
use SprykerShop\Yves\MerchantProductOfferWidget\Sorter\MerchantProductViewCollectionSorterInterface;
use SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantProductStorageClientInterface;
use SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantStorageClientInterface;
use SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToPriceProductClientInterface;
use SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToPriceProductStorageClientInterface;
use SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToProductStorageClientInterface;
use SprykerShop\Yves\MerchantProductWidget\Mapper\MerchantProductMapper;
use SprykerShop\Yves\MerchantProductWidget\Reader\MerchantProductReader;
use SprykerShop\Yves\MerchantProductWidget\Reader\MerchantProductReaderInterface;

class MerchantProductWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantProductWidget\Reader\MerchantProductReaderInterface
     */
    public function createMerchantProductReader(): MerchantProductReaderInterface
    {
        return new MerchantProductReader(
            $this->getMerchantProductStorageClient(),
            $this->getPriceProductClient(),
            $this->getPriceProductStorageClient(),
            $this->getMerchantStorageClient(),
            $this->createMerchantProductMapper(),
            $this->createMerchantProductViewCollectionSorter()
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Sorter\MerchantProductViewCollectionSorterInterface
     */
    public function createMerchantProductViewCollectionSorter(): MerchantProductViewCollectionSorterInterface
    {
        return new MerchantProductViewCollectionSorter();
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductWidget\Mapper\MerchantProductMapper
     */
    public function createMerchantProductMapper(): MerchantProductMapper
    {
        return new MerchantProductMapper();
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantProductStorageClientInterface
     */
    public function getMerchantProductStorageClient(): MerchantProductWidgetToMerchantProductStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductWidgetDependencyProvider::CLIENT_MERCHANT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantProductWidgetToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductWidgetDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToProductStorageClientInterface
     */
    public function getProductStorageClient(): MerchantProductWidgetToProductStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToPriceProductClientInterface
     */
    public function getPriceProductClient(): MerchantProductWidgetToPriceProductClientInterface
    {
        return $this->getProvidedDependency(MerchantProductWidgetDependencyProvider::CLIENT_PRICE_PRODUCT);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): MerchantProductWidgetToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductWidgetDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }
}
