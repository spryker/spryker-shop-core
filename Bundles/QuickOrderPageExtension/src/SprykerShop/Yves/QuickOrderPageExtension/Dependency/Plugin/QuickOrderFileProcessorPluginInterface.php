<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface QuickOrderFileProcessorPluginInterface
{
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
     * - Return allowed mime types of plugin.
     *
     * @api
     *
     * @return string[]
     */
    public function getAllowedMimeTypes();

    /**
     * Specification:
     * - Check if file format valid.
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
     * - Check if amount of rows are valid.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param int $maxAllowedLines
     *
     * @return bool
     */
    public function isValidAmountOfRows(UploadedFile $file, int $maxAllowedLines): bool;

    /**
     * Specification:
     * - Parse file and return array of QuickOrderItemTransfers.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function parseFile(UploadedFile $file): array;
}
