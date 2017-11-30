<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductNewPage;

use Spryker\Client\Collector\CollectorClient;
use Spryker\Yves\Kernel\AbstractFactory;

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
     * @return \Spryker\Client\ProductNew\ProductNewClientInterface
     */
    public function getProductNewClient()
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::CLIENT_PRODUCT_NEW);
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    protected function getSearchClient()
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\Collector\CollectorClientInterface
     */
    public function getCollectorClient()
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::CLIENT_COLLECTOR);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::STORE);
    }
}
