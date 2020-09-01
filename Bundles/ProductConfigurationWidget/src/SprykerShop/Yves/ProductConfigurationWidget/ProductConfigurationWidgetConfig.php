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
     * TODO: replace from `cart` to `product-configurator-gateway-request`
     */
    protected const PRODUCT_CONFIGURATION_GATEWAY_REQUEST_ROUTE = 'cart';

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
    public function getPdpSourceType(): string
    {
        return static::SOURCE_TYPE_PDP;
    }

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
    public function getProductConfigurationGatewayRequestRoute(): string
    {
        return static::PRODUCT_CONFIGURATION_GATEWAY_REQUEST_ROUTE;
    }
}
