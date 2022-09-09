<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationShoppingListWidget\Resolver;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationTemplateTransfer;

class ProductConfigurationTemplateResolver implements ProductConfigurationTemplateResolverInterface
{
    /**
     * @var array<\SprykerShop\Yves\ProductConfigurationShoppingListWidgetExtension\Dependency\Plugin\ShoppingListItemProductConfigurationRenderStrategyPluginInterface>
     */
    protected array $shoppingListItemProductConfigurationRenderStrategyPlugins;

    /**
     * @param array<\SprykerShop\Yves\ProductConfigurationShoppingListWidgetExtension\Dependency\Plugin\ShoppingListItemProductConfigurationRenderStrategyPluginInterface> $shoppingListItemProductConfigurationRenderStrategyPlugins
     */
    public function __construct(array $shoppingListItemProductConfigurationRenderStrategyPlugins)
    {
        $this->shoppingListItemProductConfigurationRenderStrategyPlugins = $shoppingListItemProductConfigurationRenderStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTemplateTransfer
     */
    public function resolveProductConfigurationTemplate(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationTemplateTransfer {
        foreach ($this->shoppingListItemProductConfigurationRenderStrategyPlugins as $shoppingListItemProductConfigurationRenderStrategyPlugin) {
            if ($shoppingListItemProductConfigurationRenderStrategyPlugin->isApplicable($productConfigurationInstanceTransfer)) {
                return $shoppingListItemProductConfigurationRenderStrategyPlugin->getTemplate($productConfigurationInstanceTransfer);
            }
        }

        return new ProductConfigurationTemplateTransfer();
    }
}
