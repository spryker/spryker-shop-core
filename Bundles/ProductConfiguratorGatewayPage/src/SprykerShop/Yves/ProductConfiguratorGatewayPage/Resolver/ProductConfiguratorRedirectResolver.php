<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Exception\MissingProductConfiguratorRequestStrategyPluginException;
use SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorRequestStrategyPluginInterface;

class ProductConfiguratorRedirectResolver implements ProductConfiguratorRedirectResolverInterface
{
    /**
     * @var array<\SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorRequestStrategyPluginInterface>
     */
    protected $productConfiguratorRequestStartegyPlugins;

    /**
     * @param array<\SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorRequestStrategyPluginInterface> $productConfiguratorRequestStartegyPlugins
     */
    public function __construct(array $productConfiguratorRequestStartegyPlugins)
    {
        $this->productConfiguratorRequestStartegyPlugins = $productConfiguratorRequestStartegyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @throws \SprykerShop\Yves\ProductConfiguratorGatewayPage\Exception\MissingProductConfiguratorRequestStrategyPluginException
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorAccessTokenRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        foreach ($this->productConfiguratorRequestStartegyPlugins as $productConfiguratorRequestStrategyPlugin) {
            if ($productConfiguratorRequestStrategyPlugin->isApplicable($productConfiguratorRequestTransfer)) {
                return $productConfiguratorRequestStrategyPlugin->resolveProductConfiguratorRedirect($productConfiguratorRequestTransfer);
            }
        }

        throw new MissingProductConfiguratorRequestStrategyPluginException(
            sprintf(
                "Missing instance of %s! You need to provide product configurator request strategy plugin
in your own ProductConfiguratorGatewayPageDependencyProvider::getProductConfiguratorRequestStrategyPlugins().",
                ProductConfiguratorRequestStrategyPluginInterface::class,
            ),
        );
    }
}
