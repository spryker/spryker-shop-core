<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductSetDetailPage;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductSetDetailPage\Dependency\Client\ProductSetDetailPageToProductStorageClientInterface;
use SprykerShop\Yves\ProductSetDetailPage\Dependency\Client\ProductSetDetailPageToProductSetStorageClientInterface;

class ProductSetDetailPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductSetDetailPage\Dependency\Client\ProductSetDetailPageToProductSetStorageClientInterface
     */
    public function getProductSetStorageClient(): ProductSetDetailPageToProductSetStorageClientInterface
    {
        return $this->getProvidedDependency(ProductSetDetailPageDependencyProvider::CLIENT_PRODUCT_SET_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductSetDetailPage\Dependency\Client\ProductSetDetailPageToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductSetDetailPageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductSetDetailPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return string[]
     */
    public function getProductSetDetailPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ProductSetDetailPageDependencyProvider::PLUGIN_PRODUCT_SET_DETAIL_PAGE_WIDGETS);
    }
}
