<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget\Form\DataProvider;

use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetConfig;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\ProductConfiguratorRequestDataForm;

class ProductConfiguratorButtonFormProductDetailPageDataProvider
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
     * @return array
     */
    public function getData(ProductViewTransfer $productViewTransfer): array
    {
        return [
           ProductConfiguratorRequestDataForm::FILED_SOURCE_TYPE => $this->config->getPdpSourceType(),
           ProductConfiguratorRequestDataForm::FILED_SKU => $productViewTransfer->getSku(),
        ];
    }
}
