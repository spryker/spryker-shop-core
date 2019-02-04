<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\File\Parser;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileValidatorInterface
{
    /**
     * Specification:
     * - Return allowed mime types of plugin.
     *
     * @api
     *
     * @return string[]
     */
    public function getAllowedMimeTypes();

    /**
     * Specification:
     * - Check if file applicable or not.
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
     * - Check if the file format is valid.
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
     * - Check if amount of rows is valid.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param int $maxAllowedRows
     *
     * @return bool
     */
    public function isValidAmountOfRows(UploadedFile $file, int $maxAllowedRows): bool;

    /**
     * Specification:
     * - Check if the file mime-type is valid.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function isValidMimeType(UploadedFile $file): bool;
}
