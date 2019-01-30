<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use InvalidArgumentException;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderItemEmbeddedForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class QuantityFieldConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $orderItemTransfer The value that should be validated
     * @param \Symfony\Component\Validator\Constraint|\SprykerShop\Yves\QuickOrderPage\Form\Constraint\QuantityFieldConstraint $constraint The constraint for the validation
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($orderItemTransfer, Constraint $constraint): void
    {
        if (!$constraint instanceof QuantityFieldConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                QuantityFieldConstraint::class,
                get_class($constraint)
            ));
        }

        if (!$orderItemTransfer->getSku()) {
            return;
        }

        if ($orderItemTransfer->getQuantity() > 0) {
            return;
        }

        $this->context
            ->buildViolation($constraint->getMessage())
            ->atPath(QuickOrderItemEmbeddedForm::FIELD_QUANTITY)
            ->addViolation();
    }
}
