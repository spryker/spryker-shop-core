<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadedFileTypeValidatorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @param int $rowCountLimit
     *
     * @return bool
     */
    public function isValidRowCount(UploadedFile $uploadedFile, int $rowCountLimit): bool;

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function validateFormat(UploadedFile $file): bool;
}
