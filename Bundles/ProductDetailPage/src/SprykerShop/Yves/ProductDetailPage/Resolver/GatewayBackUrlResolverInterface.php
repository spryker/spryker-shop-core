<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Resolver;

use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;

interface GatewayBackUrlResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return string
     */
    public function resolveBackUrl(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): string;
}
