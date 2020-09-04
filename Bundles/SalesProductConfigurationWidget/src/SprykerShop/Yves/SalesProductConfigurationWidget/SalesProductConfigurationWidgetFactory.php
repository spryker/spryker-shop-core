<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesProductConfigurationWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SalesProductConfigurationWidget\Resolver\ProductConfigurationTemplateResolver;
use SprykerShop\Yves\SalesProductConfigurationWidget\Resolver\ProductConfigurationTemplateResolverInterface;

/**
 * @method \SprykerShop\Yves\SalesProductConfigurationWidget\SalesProductConfigurationWidgetConfig getConfig()
 */
class SalesProductConfigurationWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\SalesProductConfigurationWidget\Resolver\ProductConfigurationTemplateResolverInterface
     */
    public function createProductConfigurationTemplateResolver(): ProductConfigurationTemplateResolverInterface
    {
        return new ProductConfigurationTemplateResolver(
            $this->getSalesProductConfigurationRenderStrategyPlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\SalesProductConfigurationWidgetExtension\Dependency\Plugin\SalesProductConfigurationRenderStrategyPluginInterface[]
     */
    public function getSalesProductConfigurationRenderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(SalesProductConfigurationWidgetDependencyProvider::PLUGINS_SALES_PRODUCT_CONFIGURATION_RENDER_STRATEGY);
    }
}
