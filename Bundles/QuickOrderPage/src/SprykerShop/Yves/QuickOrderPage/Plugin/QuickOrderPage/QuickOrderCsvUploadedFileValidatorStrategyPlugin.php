<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Plugin\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderUploadedFileValidatorStrategyPluginInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 */
class QuickOrderCsvUploadedFileValidatorStrategyPlugin extends AbstractPlugin implements QuickOrderUploadedFileValidatorStrategyPluginInterface
{
    protected const CSV_FILE_MIME_TYPE = 'text/csv';

    /**
     * {@inheritDoc}
     * - Returns true if the provided mime type matches the expected mime type.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function isApplicable(UploadedFile $file): bool
    {
        return $file->getClientMimeType() === static::CSV_FILE_MIME_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Returns false if less than 1 row was provided (header row).
     * - Returns false if mandatory columns are not present based by header row.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function isValidFormat(UploadedFile $file): bool
    {
        return $this->getFactory()
            ->createUploadedFileCsvTypeValidator()
            ->validateFormat($file);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param int $rowCountLimit
     *
     * @return bool
     */
    public function isValidRowCount(UploadedFile $file, int $rowCountLimit): bool
    {
        return $this->getFactory()
            ->createUploadedFileCsvTypeValidator()
            ->isValidRowCount($file, $rowCountLimit);
    }
}
