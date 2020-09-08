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

class ItemGroupKeyConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\Constraint\ItemGroupKeyConstraint|\Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ItemGroupKeyConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                ItemGroupKeyConstraint::class,
                get_class($constraint)
            ));
        }

        $sourceType = $this->context
            ->getRoot()->get(ProductConfiguratorRequestDataForm::FILED_SOURCE_TYPE)->getData();

        if ($sourceType === $constraint->getCartSourceType() && empty($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
