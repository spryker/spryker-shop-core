<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductSetListPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductSetListPage\Dependency\Client\ProductSetListPageToProductSetPageSearchClientInterface;

class ProductSetListPageFactory extends AbstractFactory
{

    /**
     * @return ProductSetListPageToProductSetPageSearchClientInterface
     */
    public function getProductSetPageSearchClient(): ProductSetListPageToProductSetPageSearchClientInterface
    {
        return $this->getProvidedDependency(ProductSetListPageDependencyProvider::CLIENT_PRODUCT_SET_PAGE_SEARCH);
    }

    /**
     * @return string[]
     */
    public function getProductSetListPageWidgets(): array
    {
        return $this->getProvidedDependency(ProductSetListPageDependencyProvider::PLUGIN_PRODUCT_SET_LIST_PAGE_WIDGETS);
    }
}
