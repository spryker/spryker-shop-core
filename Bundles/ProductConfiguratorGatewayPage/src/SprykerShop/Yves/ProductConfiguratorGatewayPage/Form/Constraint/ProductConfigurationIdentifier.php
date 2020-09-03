<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ProductConfigurationIdentifier extends SymfonyConstraint
{
    /**
     * @var string
     */
    public $message = 'product_configurator.sku_or_group_key_required';
}
