<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form\Constraint;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ShareShoppingListRequiredIdConstraintValidator extends ConstraintValidator
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_SHARE_ERROR_ONE_ID_REQUIRED = 'customer.account.shopping_list.share.error.one_id_required';

    /**
     * @param mixed|\Generated\Shared\Transfer\ShoppingListShareRequestTransfer $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }

        if (!$constraint instanceof ShareShoppingListRequiredIdConstraint) {
            throw new UnexpectedTypeException($constraint, ShareShoppingListRequiredIdConstraint::class);
        }
    }
}
