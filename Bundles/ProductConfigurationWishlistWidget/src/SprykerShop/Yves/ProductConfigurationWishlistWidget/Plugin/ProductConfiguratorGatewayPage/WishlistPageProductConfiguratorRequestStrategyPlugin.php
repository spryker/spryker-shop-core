<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget\Plugin\ProductConfiguratorGatewayPage;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorRequestStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetConfig getConfig()
 */
class WishlistPageProductConfiguratorRequestStrategyPlugin extends AbstractPlugin implements ProductConfiguratorRequestStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData::configuratorKey` to be set.
     * - Expects `ProductConfiguratorRequestTransfer::productConfiguratorRequest::sourceType` to be provided.
     * - Applicable when configurator key is supported.
     * - Applicable when source type is equal to wishlist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer): bool
    {
        return $this->getFactory()
            ->createWishlistPageApplicabilityChecker()
            ->isRequestApplicable($productConfiguratorRequestTransfer);
    }

    /**
     * {@inheritDoc}
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData` to be set.
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData::idWishlistItem` to be set.
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData::sku` to be set.
     * - Finds product configuration instance for given wishlist item.
     * - Maps product configuration instance data to `ProductConfiguratorRequestTransfer`.
     * - Sends product configurator access token request.
     * - Returns `ProductConfiguratorRedirectTransfer::isSuccessful` equal to `true` when redirect URL was successfully resolved.
     * - Returns `ProductConfiguratorRedirectTransfer::isSuccessful` equal to `false` otherwise.
     * - Returns `ProductConfiguratorRedirectTransfer::messages` with errors if any exist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        return $this->getFactory()
            ->getProductConfigurationWishlistClient()
            ->resolveProductConfiguratorAccessTokenRedirect($productConfiguratorRequestTransfer);
    }
}
