<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductSetDetailPage;

use SprykerShop\Yves\ProductSetDetailPage\ResourceCreator\ProductSetDetailPageResourceCreator;
use Spryker\Yves\Kernel\AbstractFactory;

class ProductSetDetailPageFactory extends AbstractFactory
{

    /**
     * @return \Pyz\Yves\Collector\Creator\ResourceCreatorInterface
     */
    public function createProductSetDetailPageResourceCreator()
    {
        return new ProductSetDetailPageResourceCreator(
            $this->getProductSetClient(),
            $this->getProductClient(),
            $this->getStorageProductMapperPlugin()
        );
    }

    /**
     * @return \Spryker\Client\ProductSet\ProductSetClientInterface
     */
    public function getProductSetClient()
    {
        return $this->getProvidedDependency(ProductSetDetailPageDependencyProvider::CLIENT_PRODUCT_SET);
    }

    /**
     * @return \Spryker\Client\Product\ProductClientInterface
     */
    public function getProductClient()
    {
        return $this->getProvidedDependency(ProductSetDetailPageDependencyProvider::CLIENT_PRODUCT);
    }

    /**
     * @return \SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\StorageProductMapperPluginInterface
     */
    public function getStorageProductMapperPlugin()
    {
        return $this->getProvidedDependency(ProductSetDetailPageDependencyProvider::PLUGIN_STORAGE_PRODUCT_MAPPER);
    }

}
