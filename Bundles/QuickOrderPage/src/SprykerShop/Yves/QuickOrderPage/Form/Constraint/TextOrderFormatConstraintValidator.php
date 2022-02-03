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
     * @param string|null $value
     * @param \Symfony\Component\Validator\Constraint|\SprykerShop\Yves\QuickOrderPage\Form\Constraint\TextOrderFormatConstraint $constraint The constraint for the validation
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof TextOrderFormatConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                TextOrderFormatConstraint::class,
                get_class($constraint),
            ));
        }

        if ($value === null) {
            return;
        }

        if ($this->isValidFormat($value, $constraint->getRowSplitterPattern(), $constraint->getAllowedSeparators())) {
            return;
        }

        $this->context
            ->buildViolation($constraint->getMessage())
            ->addViolation();
    }

    /**
     * @param string $textOrder
     * @param string $rowSplitterPattern
     * @param array<string> $allowedSeparators
     *
     * @return bool
     */
    protected function isValidFormat(string $textOrder, string $rowSplitterPattern, array $allowedSeparators): bool
    {
        $rows = $this->splitRows($textOrder, $rowSplitterPattern);
        if (!$rows) {
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
     * @param array<string> $rows
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
     * @return array<string>
     */
    protected function splitRows(string $textOrder, string $rowSplitterPattern): array
    {
        /** @var array<string> $array */
        $array = preg_split($rowSplitterPattern, $textOrder);

        return array_filter($array);
    }
}
