<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TextOrderFormatConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $textOrder The value that should be validated
     * @param \Symfony\Component\Validator\Constraint|\SprykerShop\Yves\QuickOrderPage\Form\Constraint\TextOrderFormatConstraint $constraint The constraint for the validation
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($textOrder, Constraint $constraint): void
    {
        if (!$constraint instanceof TextOrderFormatConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                TextOrderFormatConstraint::class,
                get_class($constraint)
            ));
        }

        if ($textOrder === null) {
            return;
        }

        if ($this->isValidFormat($textOrder, $constraint->getRowSplitterPattern(), $constraint->getAllowedSeparators())) {
            return;
        }

        $this->context
            ->buildViolation($constraint->getMessage())
            ->addViolation();
    }

    /**
     * @param string $textOrder
     * @param string $rowSplitterPattern
     * @param array $allowedSeparators
     *
     * @return bool
     */
    protected function isValidFormat(string $textOrder, string $rowSplitterPattern, array $allowedSeparators): bool
    {
        $rows = $this->splitRows($textOrder, $rowSplitterPattern);
        if (empty($rows)) {
            return true;
        }

        foreach ($allowedSeparators as $separator) {
            if (strpos($rows[0], $separator) === false) {
                continue;
            }

            if ($this->checkEachRow($rows, $separator)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string[] $rows
     * @param string $separator
     *
     * @return bool
     */
    protected function checkEachRow(array $rows, string $separator): bool
    {
        foreach ($rows as $row) {
            if (!preg_match("/\w[$separator]\d+$/", $row)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $textOrder
     * @param string $rowSplitterPattern
     *
     * @return string[]
     */
    protected function splitRows(string $textOrder, string $rowSplitterPattern): array
    {
        return array_filter(preg_split($rowSplitterPattern, $textOrder));
    }
}
