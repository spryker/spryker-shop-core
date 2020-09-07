<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget\Form\DataProvider;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetConfig;

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
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function getData(ItemTransfer $itemTransfer): ProductConfiguratorRequestDataTransfer
    {
        return (new ProductConfiguratorRequestDataTransfer())
            ->setSku($itemTransfer->getSku())
            ->setSourceType($this->config->getCartSourceType())
            ->setItemGroupKey($itemTransfer->getGroupKey())
            ->setQuantity($itemTransfer->getQuantity());
    }
}
