<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\DataProvider;

use SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\ProductConfiguratorRequestDataForm;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig;
use Symfony\Component\HttpFoundation\Request;

class ProductConfiguratorRequestDataFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig
     */
    protected $productConfiguratorGatewayPageConfig;

    /**
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig $productConfiguratorGatewayPageConfig
     */
    public function __construct(ProductConfiguratorGatewayPageConfig $productConfiguratorGatewayPageConfig)
    {
        $this->productConfiguratorGatewayPageConfig = $productConfiguratorGatewayPageConfig;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function getOptions(Request $request): array
    {
        $productConfiguratorRequestDataFormName = $this->productConfiguratorGatewayPageConfig
            ->getProductConfiguratorGatewayRequestFormName();

        $sourceType = $request->get($productConfiguratorRequestDataFormName)[ProductConfiguratorRequestDataForm::FIELD_SOURCE_TYPE] ?? null;

        return [
            ProductConfiguratorRequestDataForm::OPTION_SOURCE_TYPE => $sourceType,
        ];
    }
}
