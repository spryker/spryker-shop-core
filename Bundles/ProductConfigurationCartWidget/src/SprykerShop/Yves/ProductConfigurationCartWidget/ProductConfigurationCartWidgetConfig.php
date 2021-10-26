<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ProductConfigurationCartWidgetConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const SOURCE_TYPE_CART = 'SOURCE_TYPE_CART';

    /**
     * @uses \SprykerShop\Shared\DateTimeConfiguratorPageExample\DateTimeConfiguratorPageExampleConfig::DATE_TIME_CONFIGURATOR_KEY
     *
     * @var string
     */
    protected const DATE_TIME_CONFIGURATOR_KEY = 'DATE_TIME_CONFIGURATOR';

    /**
     * @uses \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig::PRODUCT_CONFIGURATOR_GATEWAY_REQUEST_FORM_NAME
     *
     * @var string
     */
    protected const PRODUCT_CONFIGURATOR_GATEWAY_REQUEST_FORM_NAME = 'product_configurator_request_data_form';

    /**
     * @uses \SprykerShop\Yves\ProductConfiguratorGatewayPage\Plugin\Router\ProductConfiguratorGatewayPageRouteProviderPlugin::ROUTE_NAME_PRODUCT_CONFIGURATOR_GATEWAY_REQUEST
     *
     * @var string
     */
    protected const ROUTE_NAME_PRODUCT_CONFIGURATOR_GATEWAY_REQUEST = 'product-configurator-gateway/request';

    /**
     * @api
     *
     * @return string
     */
    public function getCartSourceType(): string
    {
        return static::SOURCE_TYPE_CART;
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
     * @return string
     */
    public function getProductConfiguratorGatewayRequestRoute(): string
    {
        return static::ROUTE_NAME_PRODUCT_CONFIGURATOR_GATEWAY_REQUEST;
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
