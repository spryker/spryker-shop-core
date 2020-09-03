<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\Constraint;

use SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\ProductConfiguratorRequestDataForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductConfigurationIdentifierValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint|\SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\Constraint\ProductConfigurationIdentifier  $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        $itemGroupKeyValue = $this->context
            ->getRoot()->get(ProductConfiguratorRequestDataForm::FILED_ITEM_GROUP_KEY)->getData();

        if (empty($itemGroupKeyValue) && empty($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
