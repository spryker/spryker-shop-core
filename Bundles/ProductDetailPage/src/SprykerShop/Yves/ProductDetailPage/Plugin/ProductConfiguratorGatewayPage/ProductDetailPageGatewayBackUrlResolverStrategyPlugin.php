<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Plugin\ProductConfiguratorGatewayPage;

use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorGatewayBackUrlResolverStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductDetailPage\ProductDetailPageFactory getFactory()
 */
class ProductDetailPageGatewayBackUrlResolverStrategyPlugin extends AbstractPlugin implements ProductConfiguratorGatewayBackUrlResolverStrategyPluginInterface
{
    /**
     * @uses \Spryker\Shared\ProductConfiguration\ProductConfigurationConfig::SOURCE_TYPE_PDP
     */
    protected const SOURCE_TYPE_PDP = 'SOURCE_TYPE_PDP';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): bool
    {
        return $productConfiguratorResponseTransfer->getSourceType() === static::SOURCE_TYPE_PDP;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer
     *
     * @return string
     */
    public function resolveBackUrl(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): string
    {
        return $this->getFactory()->createGatewayBackUrlResolver()->resolveBackUrl($productConfiguratorResponseTransfer);
    }
}
