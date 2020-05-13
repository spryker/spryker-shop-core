<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage;

use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductDetailPage\Dependency\Client\ProductDetailPageToProductStorageClientInterface;
use SprykerShop\Yves\ProductDetailPage\Resolver\ShopContextResolver;
use SprykerShop\Yves\ProductDetailPage\Resolver\ShopContextResolverInterface;

class ProductDetailPageFactory extends AbstractFactory
{
    /**
     * @return string[]
     */
    public function getProductDetailPageWidgetPlugins()
    {
        return $this->getProvidedDependency(ProductDetailPageDependencyProvider::PLUGIN_PRODUCT_DETAIL_PAGE_WIDGETS);
    }

    /**
     * @return \SprykerShop\Yves\ProductDetailPage\Dependency\Client\ProductDetailPageToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductDetailPageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductDetailPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(ProductDetailPageDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \SprykerShop\Yves\ProductDetailPage\Resolver\ShopContextResolverInterface
     */
    public function createShopContextResolver(): ShopContextResolverInterface
    {
        return new ShopContextResolver($this->getApplication());
    }
}
