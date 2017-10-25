<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductSalePage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductWidget\Plugin\CatalogPage\ProductWidgetPlugin;

class ProductSalePageFactory extends AbstractFactory
{
    /**
     * @return string[]
     */
    public function getProductSalePageWidgetPlugins(): array
    {
        // TODO: move to dependency provider
        return [
            // TODO: get from project level
            ProductWidgetPlugin::class,
        ];
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    protected function getSearchClient()
    {
        return $this->getProvidedDependency(ProductSalePageDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \SprykerShop\Yves\CategoryWidget\Plugin\CategoryReaderPlugin
     */
    public function getCategoryReaderPlugin()
    {
        return $this->getProvidedDependency(ProductSalePageDependencyProvider::PLUGIN_CATEGORY_READER);
    }
}
