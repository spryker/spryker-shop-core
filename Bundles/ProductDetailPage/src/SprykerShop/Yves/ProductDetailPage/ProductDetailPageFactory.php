<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage;

use Generated\Shared\Transfer\ShopContextTransfer;
use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductDetailPage\Dependency\Client\ProductDetailPageToProductStorageClientInterface;

class ProductDetailPageFactory extends AbstractFactory
{
    protected const SERVICE_SHOP_CONTEXT = 'SERVICE_SHOP_CONTEXT';

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
     * @return \Generated\Shared\Transfer\ShopContextTransfer
     */
    public function getShopContext(): ShopContextTransfer
    {
        return $this->getApplication()[static::SERVICE_SHOP_CONTEXT];
    }
}
