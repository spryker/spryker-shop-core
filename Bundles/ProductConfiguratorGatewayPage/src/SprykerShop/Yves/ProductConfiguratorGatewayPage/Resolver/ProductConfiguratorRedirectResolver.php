<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;

class ProductConfiguratorRedirectResolver implements ProductConfiguratorRedirectResolverInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CONFIGURATOR_KEY_IS_NOT_SUPPORTED = 'product_configurator_gateway_page.configurator_key_is_not_supported';

    /**
     * @var array<\SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorRequestStrategyPluginInterface>
     */
    protected $productConfiguratorRequestStrategyPlugins;

    /**
     * @param array<\SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorRequestStrategyPluginInterface> $productConfiguratorRequestStrategyPlugins
     */
    public function __construct(array $productConfiguratorRequestStrategyPlugins)
    {
        $this->productConfiguratorRequestStrategyPlugins = $productConfiguratorRequestStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorAccessTokenRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        foreach ($this->productConfiguratorRequestStrategyPlugins as $productConfiguratorRequestStrategyPlugin) {
            if ($productConfiguratorRequestStrategyPlugin->isApplicable($productConfiguratorRequestTransfer)) {
                return $productConfiguratorRequestStrategyPlugin->resolveProductConfiguratorRedirect($productConfiguratorRequestTransfer);
            }
        }

        return (new ProductConfiguratorRedirectTransfer())
            ->setIsSuccessful(false)
            ->addMessage((new MessageTransfer())->setValue(static::GLOSSARY_KEY_CONFIGURATOR_KEY_IS_NOT_SUPPORTED));
    }
}
