<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client;

use Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceCriteriaTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

interface ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceCriteriaTransfer $productConfigurationInstanceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer
     */
    public function getProductConfigurationInstanceCollection(
        ProductConfigurationInstanceCriteriaTransfer $productConfigurationInstanceCriteriaTransfer
    ): ProductConfigurationInstanceCollectionTransfer;

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return void
     */
    public function storeProductConfigurationInstanceBySku(
        string $sku,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): void;
}
