<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage;

use Spryker\Client\Product\ProductClient;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductDetailPage\Mapper\AttributeVariantMapper;
use SprykerShop\Yves\ProductDetailPage\Mapper\StorageProductMapper;
use SprykerShop\Yves\ProductDetailPage\ResourceCreator\ProductResourceCreator;

class ProductDetailPageFactory extends AbstractFactory
{

    /**
     * @return \SprykerShop\Yves\ProductDetailPage\ResourceCreator\ProductResourceCreator
     */
    public function createProductResourceCreator()
    {
        return new ProductResourceCreator();
    }

    /**
     * @return \SprykerShop\Yves\ProductDetailPage\Mapper\StorageProductMapperInterface
     */
    public function createStorageProductMapper()
    {
        return new StorageProductMapper($this->createAttributeVariantMapper(), $this->getStorageProductExpanderPlugins());
    }

    /**
     * @return \SprykerShop\Yves\ProductDetailPage\Mapper\AttributeVariantMapperInterface
     */
    public function createAttributeVariantMapper()
    {
        return new AttributeVariantMapper($this->getProductClient());
    }

    /**
     * @return \Spryker\Client\ProductGroup\ProductGroupClientInterface
     */
    public function getProductGroupClient()
    {
        return $this->getProvidedDependency(ProductDetailPageDependencyProvider::CLIENT_PRODUCT_GROUP);
    }

    /**
     * @return \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface[]
     */
    public function getProductDetailPageWidgetPlugins()
    {
        return $this->getProvidedDependency(ProductDetailPageDependencyProvider::PLUGIN_PRODUCT_DETAIL_PAGE_WIDGETS);
    }

    /**
     * @return \SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\StorageProductExpanderPluginInterface[]
     */
    protected function getStorageProductExpanderPlugins()
    {
        return $this->getProvidedDependency(ProductDetailPageDependencyProvider::PLUGIN_STORAGE_PRODUCT_EXPANDERS);
    }

    /**
     * @return \Spryker\Client\Product\ProductClient
     */
    public function getProductClient()
    {
        return new ProductClient();
    }

}
