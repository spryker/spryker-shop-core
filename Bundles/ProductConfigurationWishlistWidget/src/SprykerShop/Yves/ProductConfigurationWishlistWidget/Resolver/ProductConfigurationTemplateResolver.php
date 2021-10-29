<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget\Resolver;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationTemplateTransfer;

class ProductConfigurationTemplateResolver implements ProductConfigurationTemplateResolverInterface
{
    /**
     * @var array<\SprykerShop\Yves\ProductConfigurationWishlistWidgetExtension\Dependency\Plugin\WishlistItemProductConfigurationRenderStrategyPluginInterface>
     */
    protected $wishlistItemProductConfigurationRenderStrategyPlugins;

    /**
     * @param array<\SprykerShop\Yves\ProductConfigurationWishlistWidgetExtension\Dependency\Plugin\WishlistItemProductConfigurationRenderStrategyPluginInterface> $wishlistItemProductConfigurationRenderStrategyPlugins
     */
    public function __construct(array $wishlistItemProductConfigurationRenderStrategyPlugins)
    {
        $this->wishlistItemProductConfigurationRenderStrategyPlugins = $wishlistItemProductConfigurationRenderStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTemplateTransfer
     */
    public function resolveProductConfigurationTemplate(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationTemplateTransfer {
        foreach ($this->wishlistItemProductConfigurationRenderStrategyPlugins as $wishlistItemProductConfigurationRenderStrategyPlugin) {
            if ($wishlistItemProductConfigurationRenderStrategyPlugin->isApplicable($productConfigurationInstanceTransfer)) {
                return $wishlistItemProductConfigurationRenderStrategyPlugin->getTemplate($productConfigurationInstanceTransfer);
            }
        }

        return new ProductConfigurationTemplateTransfer();
    }
}
