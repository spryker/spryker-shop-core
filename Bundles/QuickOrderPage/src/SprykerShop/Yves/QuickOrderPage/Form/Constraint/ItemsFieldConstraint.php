<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ItemsFieldConstraint extends SymfonyConstraint
{
    protected const MESSAGE = 'quick-order.errors.items-required';

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }
}
