<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

        if (!$file) {
            $this->context
                ->buildViolation($constraint->getNoFileMessage())
                ->addViolation();

            return;
        }

        if (!$this->isValidMimeType($file, $constraint->getFileProcessorPlugins())) {
            $this->context
                ->buildViolation($constraint->getInvalidMimeTypeMessage())
                ->addViolation();
        }

        if (!$this->isValidAmountOfRows($file, $constraint->getUploadOrderMaxAllowedLines(), $constraint->getFileProcessorPlugins())) {
            $this->context
                ->buildViolation($constraint->getInvalidAmountOfRowsMessage())
                ->addViolation();
        }

        if (!$this->isValidFormat($file, $constraint->getFileProcessorPlugins())) {
            $this->context
                ->buildViolation($constraint->getInvalidFormatMessage())
                ->addViolation();
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileProcessorPluginInterface[] $fileProcessorPlugins
     *
     * @return bool
     */
    protected function isValidMimeType(UploadedFile $file, array $fileProcessorPlugins): bool
    {
        foreach ($fileProcessorPlugins as $fileProcessorPlugin) {
            if ($fileProcessorPlugin->isApplicable($file)) {
                if (in_array($file->getClientMimeType(), $fileProcessorPlugin->getAllowedMimeTypes())) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileProcessorPluginInterface[] $fileProcessorPlugins
     *
     * @return bool
     */
    protected function isValidFormat(UploadedFile $file, array $fileProcessorPlugins): bool
    {
        foreach ($fileProcessorPlugins as $fileProcessorPlugin) {
            if ($fileProcessorPlugin->isApplicable($file)) {
                return $fileProcessorPlugin->isValidFormat($file);
            }
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param int $maxAllowedLines
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileProcessorPluginInterface[] $fileProcessorPlugins
     *
     * @return bool
     */
    protected function isValidAmountOfRows(UploadedFile $file, int $maxAllowedLines, array $fileProcessorPlugins): bool
    {
        foreach ($fileProcessorPlugins as $fileProcessorPlugin) {
            if ($fileProcessorPlugin->isApplicable($file)) {
                return $fileProcessorPlugin->isValidAmountOfRows($file, $maxAllowedLines);
            }
        }

        return false;
    }
}
