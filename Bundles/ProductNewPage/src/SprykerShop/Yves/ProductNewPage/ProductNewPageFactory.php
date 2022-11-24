<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductNewPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToCatalogClientInterface;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToLocaleClientInterface;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToProductNewClientInterface;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToUrlStorageClientInterface;
use SprykerShop\Yves\ProductNewPage\Dependency\Service\ProductNewPageToUtilNumberServiceInterface;

class ProductNewPageFactory extends AbstractFactory
{
    /**
     * @return array<string>
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
     * @return \SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToCatalogClientInterface
     */
    public function getCatalogClient(): ProductNewPageToCatalogClientInterface
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::CLIENT_CATALOG);
    }

    /**
     * @return \SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToLocaleClientInterface
     */
    public function getLocaleClient(): ProductNewPageToLocaleClientInterface
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \SprykerShop\Yves\ProductNewPage\Dependency\Service\ProductNewPageToUtilNumberServiceInterface
     */
    public function getUtilNumberService(): ProductNewPageToUtilNumberServiceInterface
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::SERVICE_UTIL_NUMBER);
    }
}
