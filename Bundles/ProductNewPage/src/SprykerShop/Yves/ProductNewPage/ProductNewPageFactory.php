<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductNewPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToCatalogClientInterface;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToProductNewClientInterface;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToUrlStorageClientInterface;

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
     * @return \SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToCatalogClientInterface
     */
    public function getCatalogClient(): ProductNewPageToCatalogClientInterface
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::CLIENT_CATALOG);
    }
}
