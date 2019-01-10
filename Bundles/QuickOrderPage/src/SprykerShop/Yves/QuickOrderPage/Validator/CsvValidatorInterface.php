<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Validator;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface CsvValidatorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @param int $maxAllowedLines
     *
     * @return bool
     */
    public function validateAmountOfRows(UploadedFile $uploadedFile, int $maxAllowedLines): bool;

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function validateFormat(UploadedFile $file): bool;
}
