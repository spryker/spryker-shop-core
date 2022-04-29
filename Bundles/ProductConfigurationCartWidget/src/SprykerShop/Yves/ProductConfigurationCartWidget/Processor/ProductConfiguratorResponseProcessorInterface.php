<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget\Processor;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;

interface ProductConfiguratorResponseProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array<string, mixed> $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer;
}
