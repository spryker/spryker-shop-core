<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface QuickOrderUploadedFileValidatorStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable to work with provided file.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function isApplicable(UploadedFile $file): bool;

    /**
     * Specification:
     * - Checks if file format valid.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function isValidFormat(UploadedFile $file): bool;

    /**
     * Specification:
     * - Checks if amount of rows are valid.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param int $maxAllowedRows
     *
     * @return bool
     */
    public function isValidAmountOfRows(UploadedFile $file, int $maxAllowedRows): bool;
}
