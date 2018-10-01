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

class QuantityRestrictionsConstraintValidator extends ConstraintValidator
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     * @param \Symfony\Component\Validator\Constraint|\SprykerShop\Yves\QuickOrderPage\Form\Constraint\QuantityRestrictionsConstraint $constraint
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($quickOrderItemTransfer, Constraint $constraint)
    {
        if (!$constraint instanceof QuantityRestrictionsConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                QuantityRestrictionsConstraint::class,
                get_class($constraint)
            ));
        }

        if (!$quickOrderItemTransfer->getSku()) {
            return;
        }

        $productQuantityValidationTransfer = $constraint->getQuickOrderClient()
            ->validateQuantityRestrictions($quickOrderItemTransfer);

        if (!$productQuantityValidationTransfer->getIsValid()) {
            $this->addViolations($productQuantityValidationTransfer->getMessages());
        }
    }

    /**
     * @param string[] $messages
     *
     * @return void
     */
    protected function addViolations(array $messages): void
    {
        foreach ($messages as $message) {
            $this->context->buildViolation($message)
                ->atPath(OrderItemEmbeddedForm::FIELD_QTY)
                ->addViolation();
        }
    }
}
