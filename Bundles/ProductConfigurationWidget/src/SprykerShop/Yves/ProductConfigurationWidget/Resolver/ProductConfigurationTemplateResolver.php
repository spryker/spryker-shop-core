<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget\Resolver;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationTemplateTransfer;

class ProductConfigurationTemplateResolver implements ProductConfigurationTemplateResolverInterface
{
    /**
     * @var \SprykerShop\Yves\ProductConfigurationWidgetExtension\Dependency\Plugin\ProductConfigurationRenderStrategyPluginInterface[]
     */
    protected $productConfigurationRenderStrategyPlugins;

    /**
     * @param \SprykerShop\Yves\ProductConfigurationWidgetExtension\Dependency\Plugin\ProductConfigurationRenderStrategyPluginInterface[] $productConfigurationRenderStrategyPlugins
     */
    public function __construct(array $productConfigurationRenderStrategyPlugins)
    {
        $this->productConfigurationRenderStrategyPlugins = $productConfigurationRenderStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTemplateTransfer
     */
    public function resolveProductConfigurationTemplate(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationTemplateTransfer {
        foreach ($this->productConfigurationRenderStrategyPlugins as $productConfigurationRenderStrategyPlugin) {
            if ($productConfigurationRenderStrategyPlugin->isApplicable($productConfigurationInstanceTransfer)) {
                return (new ProductConfigurationTemplateTransfer())
                    ->setPath($productConfigurationRenderStrategyPlugin->getTemplatePath())
                    ->setData($productConfigurationRenderStrategyPlugin->getTemplateData($productConfigurationInstanceTransfer));
            }
        }

        return new ProductConfigurationTemplateTransfer();
    }
}
