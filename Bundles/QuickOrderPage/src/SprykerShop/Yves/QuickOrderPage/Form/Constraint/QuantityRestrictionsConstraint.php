<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuickOrderClientInterface;
use Symfony\Component\Validator\Constraint;

class QuantityRestrictionsConstraint extends Constraint
{
    /**
     * @param mixed $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
    }
}
