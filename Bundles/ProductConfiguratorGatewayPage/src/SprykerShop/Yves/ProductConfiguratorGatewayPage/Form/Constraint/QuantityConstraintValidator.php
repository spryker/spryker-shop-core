<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\Constraint;

use InvalidArgumentException;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\ProductConfiguratorRequestDataForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class QuantityConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\Constraint\QuantityConstraint|\Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof QuantityConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                QuantityConstraint::class,
                get_class($constraint)
            ));
        }

        $itemGroupKeyValue = $this->context
            ->getRoot()
            ->get(ProductConfiguratorRequestDataForm::FILED_ITEM_GROUP_KEY)
            ->getData();

        if (!empty($itemGroupKeyValue) && empty($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
