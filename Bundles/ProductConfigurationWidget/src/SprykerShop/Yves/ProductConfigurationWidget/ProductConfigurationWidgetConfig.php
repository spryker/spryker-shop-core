<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ProductConfigurationWidgetConfig extends AbstractBundleConfig
{
    /**
     * @uses \SprykerShop\Yves\ProductConfiguratorGatewayPage\Plugin\Router\ProductConfiguratorGatewayPageRouteProviderPlugin::PRODUCT_CONFIGURATION_GATEWAY_REQUEST_ROUTE
     */
    protected const PRODUCT_CONFIGURATION_GATEWAY_REQUEST_ROUTE = 'product-configurator-gateway-request';

    /**
     * @uses \Spryker\Shared\ProductConfiguration\ProductConfigurationConfig::SOURCE_TYPE_PDP
     */
    protected const SOURCE_TYPE_PDP = 'SOURCE_TYPE_PDP';

    /**
     * @uses \Spryker\Shared\ProductConfiguration\ProductConfigurationConfig::SOURCE_TYPE_CART
     */
    protected const SOURCE_TYPE_CART = 'SOURCE_TYPE_CART';

    /**
     * @api
     *
     * @return string
     */
    public function getPdpSourceType()
    {
        return static::SOURCE_TYPE_PDP;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCartSourceType()
    {
        return static::SOURCE_TYPE_CART;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProductConfigurationGateRequestRoute()
    {
        return static::PRODUCT_CONFIGURATION_GATEWAY_REQUEST_ROUTE;
    }
}
