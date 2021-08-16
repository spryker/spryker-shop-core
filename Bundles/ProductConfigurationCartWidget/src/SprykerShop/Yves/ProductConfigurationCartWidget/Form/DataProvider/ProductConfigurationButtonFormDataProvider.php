<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget\Form\DataProvider;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig;

class ProductConfigurationButtonFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig
     */
    protected $config;

    /**
     * @param \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig $config
     */
    public function __construct(ProductConfigurationCartWidgetConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function getData(ItemTransfer $itemTransfer): ProductConfiguratorRequestDataTransfer
    {
        return (new ProductConfiguratorRequestDataTransfer())
            ->setSourceType($this->config->getCartSourceType())
            ->setSku($itemTransfer->getSkuOrFail())
            ->setItemGroupKey($itemTransfer->getGroupKeyOrFail())
            ->setQuantity($itemTransfer->getQuantityOrFail())
            ->setConfiguratorKey($itemTransfer->getProductConfigurationInstanceOrFail()->getConfiguratorKeyOrFail());
    }
}
