<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UploadOrderFormatConstraintValidator extends ConstraintValidator
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param \Symfony\Component\Validator\Constraint|\SprykerShop\Yves\QuickOrderPage\Form\Constraint\UploadOrderFormatConstraint $constraint The constraint for the validation
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($file, Constraint $constraint): void
    {
        if (!$constraint instanceof UploadOrderFormatConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                UploadOrderFormatConstraint::class,
                get_class($constraint)
            ));
        }

        if ($file === null) {
            $this->context
                ->buildViolation($constraint->getNoFileMessage())
                ->addViolation();

            return;
        }

        if (!$constraint->getUploadedFileValidator()->isValidMimeType($file)) {
            $this->context
                ->buildViolation($constraint->getInvalidMimeTypeMessage())
                ->addViolation();

            return;
        }

        if (!$constraint->getUploadedFileValidator()->isValidFormat($file)) {
            $this->context
                ->buildViolation($constraint->getInvalidFormatMessage())
                ->addViolation();

            return;
        }

        if (!$constraint->getUploadedFileValidator()->isValidAmountOfRows($file, $constraint->getAllowedUploadRowCount())) {
            $this->context
                ->buildViolation($constraint->getInvalidAmountOfRowsMessage())
                ->addViolation();

            return;
        }
    }
}
