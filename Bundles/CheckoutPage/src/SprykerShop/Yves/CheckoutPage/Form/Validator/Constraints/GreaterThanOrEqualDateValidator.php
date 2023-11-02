<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Validator\Constraints;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqualValidator;

/**
 * Validates date type values are greater than or equal to the previous (>=).
 */
class GreaterThanOrEqualDateValidator extends GreaterThanOrEqualValidator
{
    /**
     * @param mixed $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        $valueAsDateTime = new DateTime($value);

        parent::validate($valueAsDateTime, $constraint);
    }
}
