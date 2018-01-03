<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductNewPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToCatalogClientInterface;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToUrlStorageClientInterface;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToProductNewClientInterface;

class ProductNewPageFactory extends AbstractFactory
{
    /**
     * @return string[]
     */
    public function getProductNewPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::PLUGIN_PRODUCT_NEW_PAGE_WIDGETS);
    }

    /**
     * @return \SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToProductNewClientInterface
     */
    public function getProductNewClient(): ProductNewPageToProductNewClientInterface
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::CLIENT_PRODUCT_NEW);
    }

    /**
     * @return \SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToUrlStorageClientInterface
     */
    public function getUrlStorageClient(): ProductNewPageToUrlStorageClientInterface
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::CLIENT_URL_STORAGE);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::STORE);
    }

    /**
     * @return ProductNewPageToCatalogClientInterface
     */
    public function getCatalogClient(): ProductNewPageToCatalogClientInterface
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::CLIENT_CATALOG);
    }
}
