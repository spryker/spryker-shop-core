<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductConfigurationWidget\Reader\ProductConfigurationInstanceDataReader;
use SprykerShop\Yves\ProductConfigurationWidget\Reader\ProductConfigurationInstanceDataReaderInterface;

class ProductConfigurationWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductConfigurationWidget\Reader\ProductConfigurationInstanceDataReaderInterface
     */
    public function createProductConfigurationDataReader(): ProductConfigurationInstanceDataReaderInterface
    {
        return new ProductConfigurationInstanceDataReader($this->getProductConfigurationRendererPlugins());
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationWidgetExtension\Dependency\Plugin\ProductConfigurationRendererPluginInterface[]
     */
    public function getProductConfigurationRendererPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationWidgetDependencyProvider::PLUGIN_PRODUCT_CONFIGURATION_RENDERER);
    }
}
