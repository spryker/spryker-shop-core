<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductConfigurationWidget\Resolver\ProductConfigurationTemplateResolver;
use SprykerShop\Yves\ProductConfigurationWidget\Resolver\ProductConfigurationTemplateResolverInterface;

class ProductConfigurationWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductConfigurationWidget\Resolver\ProductConfigurationTemplateResolverInterface
     */
    public function createProductConfigurationTemplateResolver(): ProductConfigurationTemplateResolverInterface
    {
        return new ProductConfigurationTemplateResolver(
            $this->getProductConfigurationRenderStrategyPlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationWidgetExtension\Dependency\Plugin\ProductConfigurationRenderStrategyPluginInterface[]
     */
    public function getProductConfigurationRenderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationWidgetDependencyProvider::PLUGINS_PRODUCT_CONFIGURATION_RENDER_STRATEGY);
    }
}
