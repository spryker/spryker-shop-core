<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget\Form\DataProvider;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetConfig;

class ProductConfiguratorButtonFormDataProvider
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
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function getData(ProductViewTransfer $productViewTransfer): ProductConfiguratorRequestDataTransfer
    {
        return (new ProductConfiguratorRequestDataTransfer())
            ->setSku($productViewTransfer->getSku())
            ->setConfiguratorKey($productViewTransfer->getProductConfigurationInstanceOrFail()->getConfiguratorKeyOrFail())
            ->setSourceType($this->config->getPdpSourceType());
    }
}
