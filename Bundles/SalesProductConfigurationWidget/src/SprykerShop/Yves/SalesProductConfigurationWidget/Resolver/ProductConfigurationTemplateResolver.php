<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesProductConfigurationWidget\Resolver;

use Generated\Shared\Transfer\ProductConfigurationTemplateTransfer;
use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;

class ProductConfigurationTemplateResolver implements ProductConfigurationTemplateResolverInterface
{
    /**
     * @var array<\SprykerShop\Yves\SalesProductConfigurationWidgetExtension\Dependency\Plugin\SalesProductConfigurationRenderStrategyPluginInterface>
     */
    protected $salesProductConfigurationRenderStrategyPlugins;

    /**
     * @param array<\SprykerShop\Yves\SalesProductConfigurationWidgetExtension\Dependency\Plugin\SalesProductConfigurationRenderStrategyPluginInterface> $salesProductConfigurationRenderStrategyPlugins
     */
    public function __construct(array $salesProductConfigurationRenderStrategyPlugins)
    {
        $this->salesProductConfigurationRenderStrategyPlugins = $salesProductConfigurationRenderStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTemplateTransfer
     */
    public function resolveProductConfigurationTemplate(
        SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
    ): ProductConfigurationTemplateTransfer {
        foreach ($this->salesProductConfigurationRenderStrategyPlugins as $salesProductConfigurationRenderStrategyPlugin) {
            if ($salesProductConfigurationRenderStrategyPlugin->isApplicable($salesOrderItemConfigurationTransfer)) {
                return $salesProductConfigurationRenderStrategyPlugin->getTemplate($salesOrderItemConfigurationTransfer);
            }
        }

        return new ProductConfigurationTemplateTransfer();
    }
}
