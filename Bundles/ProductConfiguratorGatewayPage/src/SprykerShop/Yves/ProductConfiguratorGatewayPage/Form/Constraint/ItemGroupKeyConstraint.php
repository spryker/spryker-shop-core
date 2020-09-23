<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ItemGroupKeyConstraint extends SymfonyConstraint
{
    public const PRODUCT_CONFIGURATOR_GATEWAY_PAGE_CONFIG_KEY = 'config';

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig
     */
    protected $config;

    /**
     * @var string
     */
    public $message = 'product_configurator_gateway_page.item_group_key_required';

    /**
     * @return string
     */
    public function getCartSourceType(): string
    {
        return $this->config->getCartSourceType();
    }
}
