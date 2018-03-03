<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TextOrderCorrectConstraintValidator extends ConstraintValidator implements TextOrderCorrectConstraintValidatorInterface
{
    /**
     * @param mixed $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint|\SprykerShop\Yves\QuickOrderPage\Form\Constraint\TextOrderCorrectConstraint $constraint The constraint for the validation
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        $allowedSeparators = $constraint->getAllowedSeparators();
        if (!$this->checkFormat($value, $allowedSeparators)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ separators }}', '"' . implode('" "', $allowedSeparators) . '"')
                ->addViolation();
        }
    }

    /**
     * @param string $textOrder
     * @param array $allowedSeparators
     *
     * @return bool
     */
    public function checkFormat(string $textOrder, array $allowedSeparators): bool
    {
        foreach ($allowedSeparators as $separator) {
            if (strpos($textOrder, $separator) !== false && $this->checkEachRow($textOrder, $separator)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $textOrder
     * @param string $separator
     *
     * @return bool
     */
    protected function checkEachRow(string $textOrder, string $separator): bool
    {
        $rows = $this->getTextOrderRows($textOrder);
        foreach ($rows as $row) {
            if (strpos($row, $separator) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $textOrder
     *
     * @return array
     */
    protected function getTextOrderRows(string $textOrder): array
    {
        return array_filter(preg_split('/\r\n|\r|\n/', $textOrder));
    }
}
