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
     * @uses \Spryker\Shared\ProductConfiguration\ProductConfigurationConfig::SOURCE_TYPE_PDP
     */
    protected const SOURCE_TYPE_PDP = 'SOURCE_TYPE_PDP';

    /**
     * @uses \Spryker\Shared\ProductConfiguration\ProductConfigurationConfig::SOURCE_TYPE_CART
     */
    protected const SOURCE_TYPE_CART = 'SOURCE_TYPE_CART';

    protected const PRODUCT_CONFIGURATOR_GATEWAY_REQUEST_FORM_NAME = 'product_configurator_request_data_form';

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
    public function getProductConfiguratorGatewayRequestFormName()
    {
        return static::PRODUCT_CONFIGURATOR_GATEWAY_REQUEST_FORM_NAME;
    }
}
