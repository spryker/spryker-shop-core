<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CompanyUserRelationConstraintValidator extends ConstraintValidator
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
        if (!$constraint instanceof CompanyUserRelationConstraint) {
            throw new UnexpectedTypeException($constraint, CompanyUserRelationConstraint::class);
        }

        if ($this->isValidCompanyUser((int)$value, $constraint)) {
            return;
        }

        $this->context
            ->buildViolation($constraint->getMessage())
            ->addViolation();
    }

    /**
     * @param int $idCompany
     * @param \SprykerShop\Yves\CompanyPage\Form\Constraint\CompanyUserRelationConstraint $constraint
     *
     * @return bool
     */
    protected function isValidCompanyUser(int $idCompany, CompanyUserRelationConstraint $constraint): bool
    {
        $customerTransfer = $constraint->getCustomerClient()->getCustomer();
        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            return false;
        }

        $companyUserTransfer = $customerTransfer->getCompanyUserTransferOrFail();

        return $companyUserTransfer->getFkCompany() && $companyUserTransfer->getFkCompanyOrFail() === $idCompany;
    }
}
