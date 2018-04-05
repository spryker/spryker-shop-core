<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use SprykerShop\Yves\QuickOrderPage\Form\OrderItemEmbeddedForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class QtyFieldConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $orderItemTransfer The value that should be validated
     * @param \Symfony\Component\Validator\Constraint|\SprykerShop\Yves\QuickOrderPage\Form\Constraint\QtyFieldConstraint $constraint The constraint for the validation
     *
     * @return void
     */
    public function validate($orderItemTransfer, Constraint $constraint): void
    {
        if ($orderItemTransfer->getSku() && (!$orderItemTransfer->getQty() || $orderItemTransfer->getQty() < 1)) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath(OrderItemEmbeddedForm::FIELD_QTY)
                ->addViolation();
        }
    }
}
