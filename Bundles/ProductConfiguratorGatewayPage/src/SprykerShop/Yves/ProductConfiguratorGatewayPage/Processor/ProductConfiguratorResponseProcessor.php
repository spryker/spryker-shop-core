<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Processor;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Exception\MissingProductConfiguratorResponseStrategyPluginException;
use SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorResponseStrategyPluginInterface;

class ProductConfiguratorResponseProcessor implements ProductConfiguratorResponseProcessorInterface
{
    /**
     * @var array<\SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorResponseStrategyPluginInterface>
     */
    protected $productConfiguratorResponsePlugins;

    /**
     * @param array<\SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorResponseStrategyPluginInterface> $productConfiguratorResponsePlugins
     */
    public function __construct(array $productConfiguratorResponsePlugins)
    {
        $this->productConfiguratorResponsePlugins = $productConfiguratorResponsePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @throws \SprykerShop\Yves\ProductConfiguratorGatewayPage\Exception\MissingProductConfiguratorResponseStrategyPluginException
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        foreach ($this->productConfiguratorResponsePlugins as $productConfiguratorResponsePlugin) {
            if ($productConfiguratorResponsePlugin->isApplicable($productConfiguratorResponseTransfer)) {
                return $productConfiguratorResponsePlugin->processProductConfiguratorResponse(
                    $productConfiguratorResponseTransfer,
                    $configuratorResponseData,
                );
            }
        }

        throw new MissingProductConfiguratorResponseStrategyPluginException(
            sprintf(
                "Missing instance of %s! You need to provide product configurator response strategy plugin
in your own ProductConfiguratorGatewayPageDependencyProvider::getProductConfiguratorResponsePlugins().",
                ProductConfiguratorResponseStrategyPluginInterface::class,
            ),
        );
    }
}
