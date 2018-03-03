<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use Symfony\Component\Validator\Constraint;

interface TextOrderCorrectConstraintValidatorInterface
{
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint|\SprykerShop\Yves\QuickOrderPage\Form\Constraint\TextOrderCorrectConstraint $constraint The constraint for the validation
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void;

    /**
     * @param string $textOrder
     * @param array $allowedSeparators
     *
     * @return bool
     */
    public function checkFormat(string $textOrder, array $allowedSeparators): bool;
}
