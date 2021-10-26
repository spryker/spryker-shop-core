<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ProductConfiguratorGatewayPageConfig extends AbstractBundleConfig
{
    /**
     * @uses \SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetConfig::SOURCE_TYPE_PDP
     *
     * @var string
     */
    protected const SOURCE_TYPE_PDP = 'SOURCE_TYPE_PDP';

    /**
     * @uses \SprykerShop\Shared\DateTimeConfiguratorPageExample\DateTimeConfiguratorPageExampleConfig::DATE_TIME_CONFIGURATOR_KEY
     *
     * @var string
     */
    protected const DATE_TIME_CONFIGURATOR_KEY = 'DATE_TIME_CONFIGURATOR';

    /**
     * @var string
     */
    protected const PRODUCT_CONFIGURATOR_GATEWAY_REQUEST_FORM_NAME = 'product_configurator_request_data_form';

    /**
     * @api
     *
     * @return string
     */
    public function getPdpSourceType(): string
    {
        return static::SOURCE_TYPE_PDP;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProductConfiguratorGatewayRequestFormName(): string
    {
        return static::PRODUCT_CONFIGURATOR_GATEWAY_REQUEST_FORM_NAME;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getSupportedConfiguratorKeys(): array
    {
        return [
            static::DATE_TIME_CONFIGURATOR_KEY,
        ];
    }
}
