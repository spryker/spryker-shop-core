<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;

interface ProductConfiguratorRequestDataMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function mapProductConfigurationInstanceTransferToProductConfiguratorRequestDataTransfer(
        ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfiguratorRequestDataTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     * @param array $forms
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function mapProductConfiguratorRequestDataFormToProductConfiguratorRequestDataTransfer(
        ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer,
        array $forms
    ): ProductConfiguratorRequestDataTransfer;
}
