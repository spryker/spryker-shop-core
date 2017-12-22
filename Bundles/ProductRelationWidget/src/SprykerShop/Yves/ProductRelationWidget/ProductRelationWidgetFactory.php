<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductRelationWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductRelationWidget\Dependency\Client\ProductRelationWidgetToProductRelationStorageClientInterface;

class ProductRelationWidgetFactory extends AbstractFactory
{

    /**
     * @return string[]
     */
    public function getProductDetailPageSimilarProductsWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ProductRelationWidgetDependencyProvider::PLUGIN_PRODUCT_DETAIL_PAGE_SIMILAR_PRODUCTS_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getCartPageUpSellingProductsWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ProductRelationWidgetDependencyProvider::PLUGIN_CART_PAGE_UP_SELLING_PRODUCTS_WIDGETS);
    }

    /**
     * @return ProductRelationWidgetToProductRelationStorageClientInterface
     */
    public function getProductRelationStorageClient(): ProductRelationWidgetToProductRelationStorageClientInterface
    {
        return $this->getProvidedDependency(ProductRelationWidgetDependencyProvider::CLIENT_PRODUCT_RELATION_STORAGE);
    }

}
