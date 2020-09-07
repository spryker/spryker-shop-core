<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget\Form\DataProvider;

use Generated\Shared\Transfer\ItemTransfer;
use SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetConfig;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\ProductConfiguratorRequestDataForm;

class ProductConfiguratorButtonFormCartPageDataProvider
{
    /**
     * @var \SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetConfig
     */
    protected $config;

    /**
     * @param \SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetConfig $config
     */
    public function __construct(ProductConfigurationWidgetConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    public function getData(ItemTransfer $itemTransfer): array
    {
        return [
           ProductConfiguratorRequestDataForm::FILED_SOURCE_TYPE => $this->config->getCartSourceType(),
           ProductConfiguratorRequestDataForm::FILED_ITEM_GROUP_KEY => $itemTransfer->getGroupKey(),
           ProductConfiguratorRequestDataForm::FILED_QUANTITY => $itemTransfer->getQuantity(),
           ProductConfiguratorRequestDataForm::FILED_SKU => $itemTransfer->getSku(),
        ];
    }
}
