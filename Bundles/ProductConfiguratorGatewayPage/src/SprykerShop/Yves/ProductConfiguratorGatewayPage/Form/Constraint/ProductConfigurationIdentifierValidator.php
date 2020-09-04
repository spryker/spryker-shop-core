<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\Constraint;

use SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\ProductConfiguratorRequestDataForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProductConfigurationIdentifierValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductConfigurationIdentifier) {
            throw new UnexpectedTypeException($constraint, ProductConfigurationIdentifier::class);
        }

        $itemGroupKeyValue = $this->context
            ->getRoot()->get(ProductConfiguratorRequestDataForm::FILED_ITEM_GROUP_KEY)->getData();

        if (empty($itemGroupKeyValue) && empty($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
