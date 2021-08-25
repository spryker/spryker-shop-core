<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Checker;

use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;

interface ProductDetailPageApplicabilityCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return bool
     */
    public function isRequestApplicable(ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return bool
     */
    public function isResponseApplicable(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): bool;
}
