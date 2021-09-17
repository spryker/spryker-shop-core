<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget\Resolver;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationTemplateTransfer;

class ProductConfigurationTemplateResolver implements ProductConfigurationTemplateResolverInterface
{
    /**
     * @var array<\SprykerShop\Yves\ProductConfigurationCartWidgetExtension\Dependency\Plugin\CartProductConfigurationRenderStrategyPluginInterface>
     */
    protected $cartProductConfigurationRenderStrategyPlugins;

    /**
     * @param array<\SprykerShop\Yves\ProductConfigurationCartWidgetExtension\Dependency\Plugin\CartProductConfigurationRenderStrategyPluginInterface> $cartProductConfigurationRenderStrategyPlugins
     */
    public function __construct(array $cartProductConfigurationRenderStrategyPlugins)
    {
        $this->cartProductConfigurationRenderStrategyPlugins = $cartProductConfigurationRenderStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTemplateTransfer
     */
    public function resolveProductConfigurationTemplate(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationTemplateTransfer {
        foreach ($this->cartProductConfigurationRenderStrategyPlugins as $cartProductConfigurationRenderStrategyPlugin) {
            if ($cartProductConfigurationRenderStrategyPlugin->isApplicable($productConfigurationInstanceTransfer)) {
                return $cartProductConfigurationRenderStrategyPlugin->getTemplate($productConfigurationInstanceTransfer);
            }
        }

        return new ProductConfigurationTemplateTransfer();
    }
}
