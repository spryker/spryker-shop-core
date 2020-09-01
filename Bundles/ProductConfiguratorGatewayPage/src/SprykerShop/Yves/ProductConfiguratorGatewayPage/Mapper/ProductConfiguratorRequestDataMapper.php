<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\ProductConfiguratorRequestDataForm;

class ProductConfiguratorRequestDataMapper implements ProductConfiguratorRequestDataMapperInterface
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
    ): ProductConfiguratorRequestDataTransfer {
        return $productConfiguratorRequestDataTransfer->fromArray(
            $productConfigurationInstanceTransfer->toArray(),
            true
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     * @param array $forms
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function mapProductConfiguratorRequestDataFormToProductConfiguratorRequestDataTransfer(
        ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer,
        array $forms
    ): ProductConfiguratorRequestDataTransfer {
        $sourceTypeForm = $forms[ProductConfiguratorRequestDataForm::FILED_SOURCE_TYPE];
        $itemGroupKeyForm = $forms[ProductConfiguratorRequestDataForm::FILED_ITEM_GROUP_KEY];
        $skuForm = $forms[ProductConfiguratorRequestDataForm::FILED_SKU];
        $quantityForm = $forms[ProductConfiguratorRequestDataForm::FILED_QUANTITY];

        $productConfiguratorRequestDataTransfer
            ->setItemGroupKey($itemGroupKeyForm ? $itemGroupKeyForm->getData() : null)
            ->setSourceType($sourceTypeForm ? $sourceTypeForm->getData() : null)
            ->setSku($skuForm ? $skuForm->getData() : null)
            ->setQuantity($quantityForm ? $quantityForm->getData() : null);

        return $productConfiguratorRequestDataTransfer;
    }
}
