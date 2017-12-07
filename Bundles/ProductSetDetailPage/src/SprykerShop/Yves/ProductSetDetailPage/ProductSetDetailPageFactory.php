<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductSetDetailPage;

use Spryker\Client\Product\ProductClientInterface;
use Spryker\Client\ProductSet\ProductSetClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class ProductSetDetailPageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductSet\ProductSetClientInterface
     */
    public function getProductSetClient(): ProductSetClientInterface
    {
        return $this->getProvidedDependency(ProductSetDetailPageDependencyProvider::CLIENT_PRODUCT_SET);
    }

    /**
     * @return \Spryker\Client\Product\ProductClientInterface
     */
    public function getProductClient(): ProductClientInterface
    {
        return $this->getProvidedDependency(ProductSetDetailPageDependencyProvider::CLIENT_PRODUCT);
    }

    /**
     * @return string[]
     */
    public function getProductSetDetailPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ProductSetDetailPageDependencyProvider::PLUGIN_PRODUCT_SET_DETAIL_PAGE_WIDGETS);
    }
}
