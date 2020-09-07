<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class Quantity extends SymfonyConstraint
{
    /**
     * @var string
     */
    public $message = 'product_configurator_gateway_page.quantity_required';
}
