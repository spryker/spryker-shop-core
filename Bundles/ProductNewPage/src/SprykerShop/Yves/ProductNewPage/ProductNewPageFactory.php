<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductNewPage;

use Spryker\Client\ProductNew\ProductNewClient;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductWidget\Plugin\CatalogPage\ProductWidgetPlugin;

class ProductNewPageFactory extends AbstractFactory
{
    /**
     * @return string[]
     */
    public function getNewProductPageWidgetPlugins(): array
    {
        // TODO: get from dependency provider
        return [
            // TODO: get from project level
            ProductWidgetPlugin::class,
        ];
    }

    /**
     * @return \Spryker\Client\ProductNew\ProductNewClientInterface
     */
    public function getProductNewClient()
    {
        // TODO: use bridge + get from dependency provider
        return new ProductNewClient();
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    protected function getSearchClient()
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \SprykerShop\Yves\CategoryWidget\Plugin\CategoryReaderPlugin
     */
    public function getCategoryReaderPlugin()
    {
        return $this->getProvidedDependency(ProductNewPageDependencyProvider::PLUGIN_CATEGORY_READER);
    }
}
