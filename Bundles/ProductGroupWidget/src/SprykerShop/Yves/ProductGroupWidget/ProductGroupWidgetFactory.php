<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductGroupWidget\Dependency\Client\ProductGroupWidgetToProductGroupStorageClientInterface;
use SprykerShop\Yves\ProductGroupWidget\Dependency\Client\ProductGroupWidgetToProductStorageClientInterface;

class ProductGroupWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductGroupWidget\Dependency\Client\ProductGroupWidgetToProductGroupStorageClientInterface
     */
    public function getProductGroupStorageClient(): ProductGroupWidgetToProductGroupStorageClientInterface
    {
        return $this->getProvidedDependency(ProductGroupWidgetDependencyProvider::CLIENT_PRODUCT_GROUP_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductGroupWidget\Dependency\Client\ProductGroupWidgetToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductGroupWidgetToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductGroupWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductGroupWidgetExtension\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    public function getProductViewExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductGroupWidgetDependencyProvider::PLUGIN_PRODUCT_VIEW_EXPANDERS);
    }
}
