<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\Constraint;

use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CompanyUserCustomerRelationConstraintValidator extends ConstraintValidator
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
        if (!$constraint instanceof CompanyUserCustomerRelationConstraint) {
            throw new UnexpectedTypeException($constraint, CompanyUserCustomerRelationConstraint::class);
        }

        if ($this->isValidCustomer((int)$value, $constraint)) {
            return;
        }

        $this->context
            ->buildViolation($constraint->getMessage())
            ->addViolation();
    }

    /**
     * @param int $idCustomer
     * @param \SprykerShop\Yves\CompanyPage\Form\Constraint\CompanyUserCustomerRelationConstraint $constraint
     *
     * @return bool
     */
    protected function isValidCustomer(int $idCustomer, CompanyUserCustomerRelationConstraint $constraint): bool
    {
        $customerTransfer = $constraint->getCustomerClient()->getCustomer();
        $customerRequestTransfer = (new CustomerTransfer())->setIdCustomer($idCustomer);
        $customerFromFormTransfer = $constraint->getCustomerClient()->findCustomerById($customerRequestTransfer);

        if ($customerTransfer === null || $customerTransfer->getCompanyUserTransfer() === null) {
            return false;
        }

        if ($customerFromFormTransfer === null || $customerFromFormTransfer->getCompanyUserTransfer() === null) {
            return false;
        }

        $companyUserTransfer = $customerTransfer->getCompanyUserTransferOrFail();
        $companyUserTransferFromForm = $customerFromFormTransfer->getCompanyUserTransferOrFail();

        return $companyUserTransfer->getFkCompany() && $companyUserTransfer->getFkCompany() === $companyUserTransferFromForm->getFkCompany();
    }
}
