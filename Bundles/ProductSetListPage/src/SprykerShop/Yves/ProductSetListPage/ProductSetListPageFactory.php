<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductSetListPage;

use Spryker\Client\ProductSet\ProductSetClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class ProductSetListPageFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\ProductSet\ProductSetClientInterface
     */
    public function getProductSetClient(): ProductSetClientInterface
    {
        return $this->getProvidedDependency(ProductSetListPageDependencyProvider::CLIENT_PRODUCT_SET);
    }

    /**
     * @return string[]
     */
    public function getProductSetListPageWidgets(): array
    {
        return $this->getProvidedDependency(ProductSetListPageDependencyProvider::PLUGIN_PRODUCT_SET_LIST_PAGE_WIDGETS);
    }

}
