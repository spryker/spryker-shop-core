<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use InvalidArgumentException;
use SprykerShop\Yves\QuickOrderPage\Form\OrderItemEmbeddedForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ItemsFieldConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $orderItemTransfers The value that should be validated
     * @param \Symfony\Component\Validator\Constraint|\SprykerShop\Yves\QuickOrderPage\Form\Constraint\ItemsFieldConstraint $constraint The constraint for the validation
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($orderItemTransfers, Constraint $constraint): void
    {
        if (!$constraint instanceof ItemsFieldConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                ItemsFieldConstraint::class,
                get_class($constraint)
            ));
        }

        $firstOrderItemTransfer = $orderItemTransfers[0] ?? null;
        if ($firstOrderItemTransfer && !$firstOrderItemTransfer->getSku()) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('[0]')
                ->atPath(OrderItemEmbeddedForm::FIELD_SKU)
                ->addViolation();
        }
    }
}
