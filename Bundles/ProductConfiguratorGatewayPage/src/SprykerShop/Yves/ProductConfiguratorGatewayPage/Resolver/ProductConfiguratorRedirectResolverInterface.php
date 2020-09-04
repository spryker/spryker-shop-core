<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;

interface ProductConfiguratorRedirectResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorRedirect(
        ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
    ): ProductConfiguratorRedirectTransfer;
}
